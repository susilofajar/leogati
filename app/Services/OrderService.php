<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\ShippingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected CouponService $couponService,
        protected ShippingService $shippingService
    ) {}

    /**
     * Create order atomically from cart items.
     * Price, stock, and coupon discounts are strictly re-calculated and locked server-side.
     */
    public function createOrder(User $user, Cart $cart, array $checkoutData): Order
    {
        if ($cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang belanja Anda kosong.',
            ]);
        }

        return DB::transaction(function () use ($user, $cart, $checkoutData) {
            $subtotal         = 0;
            $totalWeightGrams = 0;
            $orderItemsData   = [];
            $variantsToDeduct = [];

            // 1. Validate and Lock Stock, Recalculate Subtotal from Database
            foreach ($cart->items as $cartItem) {
                // Lock row for update to prevent concurrent race condition
                $variant = ProductVariant::where('id', $cartItem->product_variant_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($variant->stock < $cartItem->quantity) {
                    throw ValidationException::withMessages([
                        'stock' => "Stok produk '{$variant->product->name} - {$variant->name}' tidak mencukupi. Sisa stok: {$variant->stock}.",
                    ]);
                }

                // Server-side price calculation — never trust browser
                $itemSubtotal      = $variant->price * $cartItem->quantity;
                $subtotal          += $itemSubtotal;
                $totalWeightGrams  += ($variant->weight_grams * $cartItem->quantity);

                $orderItemsData[] = [
                    'product_variant_id' => $variant->id,
                    'product_name'       => $variant->product->name,
                    'variant_name'       => $variant->name,
                    'sku'                => $variant->sku,
                    'unit_price'         => $variant->price,
                    'quantity'           => $cartItem->quantity,
                    'subtotal'           => $itemSubtotal,
                    'weight_grams'       => $variant->weight_grams,
                ];

                // Kumpulkan varian & jumlah untuk dikurangi stoknya via InventoryService
                $variantsToDeduct[] = ['variant' => $variant, 'quantity' => $cartItem->quantity];
            }

            // 2. Calculate Shipping Cost using Shipping Service
            $shippingCost = $this->calculateShippingCost(
                $checkoutData['shipping_courier'],
                $totalWeightGrams,
                $checkoutData
            );
            
            // 3. Process Coupon Discount Server-Side
            $discount   = 0;
            $couponId   = null;
            $couponCode = null;

            if (!empty($checkoutData['coupon_code'])) {
                $coupon = $this->couponService->validateCoupon($checkoutData['coupon_code'], $subtotal);
                $discount   = $this->couponService->calculateDiscount($coupon, $subtotal);
                $couponId   = $coupon->id;
                $couponCode = $coupon->code;

                // Increment pemakaian kupon
                $coupon->increment('used_count');
            }

            $grandTotal = max(0, ($subtotal + $shippingCost - $discount));

            // 4. Generate Unique Order Number: LEO-YYYYMMDD-XXXX
            $orderNumber = $this->generateOrderNumber();

            // 5. Create Order Record
            $order = Order::create([
                'user_id'          => $user->id,
                'order_number'     => $orderNumber,
                'subtotal_amount'  => $subtotal,
                'shipping_amount'  => $shippingCost,
                'discount_amount'  => $discount,
                'coupon_id'        => $couponId,
                'coupon_code'      => $couponCode,
                'total_amount'     => $grandTotal,
                'status'           => 'awaiting_payment',
                'payment_method'   => $checkoutData['payment_method'],
                'payment_status'   => 'unpaid',
                'shipping_courier' => $checkoutData['shipping_courier'],
                'shipping_service' => $checkoutData['shipping_service'] ?? 'Reguler',
                'shipping_address' => [
                    'recipient_name' => $checkoutData['recipient_name'],
                    'phone_number'   => $checkoutData['phone_number'],
                    'address_line'   => $checkoutData['address_line'],
                    'city'           => $checkoutData['city'],
                    'province'       => $checkoutData['province'],
                    'postal_code'    => $checkoutData['postal_code'],
                ],
                'notes' => $checkoutData['notes'] ?? null,
            ]);

            // 5. Create Order Items
            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            // 6. Deduct Inventory via InventoryService (records movement & audit trail)
            foreach ($variantsToDeduct as $entry) {
                try {
                    $this->inventoryService->deductSaleStock(
                        $entry['variant'],
                        $entry['quantity'],
                        $order,
                        $user
                    );
                } catch (ValidationException $e) {
                    // Fallback: langsung decrement jika InventoryService tidak menemukan gudang
                    $entry['variant']->decrement('stock', $entry['quantity']);
                }
            }

            // 7. Create Initial Payment Record
            Payment::create([
                'order_id'       => $order->id,
                'payment_number' => 'PAY-' . strtoupper(Str::random(10)),
                'amount'         => $grandTotal,
                'payment_method' => $checkoutData['payment_method'],
                'status'         => 'pending',
            ]);

            // 8. Clear Cart Items
            $cart->items()->delete();

            // 9. Send In-App Notification to Customer
            try {
                $user->notify(new \App\Notifications\OrderCreatedNotification($order));
            } catch (\Throwable $e) {
                // Ignore notification failure in transaction
            }

            // 10. Audit Log Trail
            AuditLogService::log(
                action: 'order_created',
                targetType: 'Order',
                targetId: $order->id,
                payload: [
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'payment_method' => $order->payment_method,
                ],
                userId: $user->id,
                userName: $user->name
            );

            return $order;
        });
    }

    /**
     * Calculate shipping rate using Shipping Service
     */
    protected function calculateShippingCost(string $courier, int $weightGrams, array $checkoutData): float
    {
        try {
            // Get origin from warehouse or default location
            $origin = [
                'city' => config('shipping.origin_city', 'Jakarta Pusat'),
                'district' => config('shipping.origin_district', 'Gambir'),
            ];

            // Get destination from checkout data
            $destination = [
                'city' => $checkoutData['city'] ?? 'Jakarta',
                'district' => $checkoutData['district'] ?? 'Jakarta Pusat',
            ];

            // Calculate using Shipping Service
            $result = $this->shippingService->calculateShippingCost(
                $origin,
                $destination,
                $weightGrams,
                $courier
            );

            if ($result['success'] && !empty($result['services'])) {
                // Get the cost from the first available service or specific service
                $serviceCode = $checkoutData['shipping_service'] ?? array_key_first($result['services']);
                $service = collect($result['services'])->firstWhere('service_code', $serviceCode);

                if ($service) {
                    return (float) $service['cost'];
                }

                // Fallback to first service
                return (float) $result['services'][0]['cost'];
            }

            // Fallback to calculation if shipping service fails
            return $this->fallbackShippingCost($courier, $weightGrams);

        } catch (\Exception $e) {
            Log::error('Shipping Cost Calculation Error: ' . $e->getMessage());
            return $this->fallbackShippingCost($courier, $weightGrams);
        }
    }

    /**
     * Fallback shipping cost calculation
     */
    protected function fallbackShippingCost(string $courier, int $weightGrams): float
    {
        $weightKg = max(1, ceil($weightGrams / 1000));

        $baseRates = [
            'jne'     => 15000,
            'sicepat' => 14000,
            'jnt'     => 16000,
        ];

        $ratePerKg = $baseRates[$courier] ?? 15000;
        return (float) ($ratePerKg * $weightKg);
    }

    /**
     * Generate unique sequential order number: LEO-YYYYMMDD-XXXX
     */
    protected function generateOrderNumber(): string
    {
        $datePrefix  = 'LEO-' . Carbon::now()->format('Ymd');
        $random      = strtoupper(Str::random(4));
        $orderNumber = "{$datePrefix}-{$random}";

        while (Order::where('order_number', $orderNumber)->exists()) {
            $random      = strtoupper(Str::random(4));
            $orderNumber = "{$datePrefix}-{$random}";
        }

        return $orderNumber;
    }
}
