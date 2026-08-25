<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\CouponService;
use App\Services\MidtransService;
use App\Services\OrderService;
use App\Services\ShippingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
        protected CouponService $couponService,
        protected MidtransService $midtransService,
        protected ShippingService $shippingService
    ) {}

    /**
     * Show checkout page with address selection, couriers, and payment methods.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $cart = $this->cartService->getCart($request->user());

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $user = $request->user();
        $primaryAddress = $user->addresses()->where('is_primary', true)->first()
            ?? $user->addresses()->latest()->first();

        $appliedCoupon = null;
        $discountAmount = 0;

        if ($code = session('applied_coupon')) {
            try {
                $appliedCoupon = $this->couponService->validateCoupon($code, $cart->subtotal);
                $discountAmount = $this->couponService->calculateDiscount($appliedCoupon, $cart->subtotal);
            } catch (ValidationException $e) {
                session()->forget('applied_coupon');
            }
        }

        // Get available couriers
        $couriers = $this->shippingService->getAvailableCouriers();

        // Calculate total weight
        $totalWeightGrams = $cart->items->sum(function ($item) {
            return ($item->variant->weight_grams ?? 500) * $item->quantity;
        });

        return view('checkout.index', compact(
            'cart',
            'user',
            'primaryAddress',
            'appliedCoupon',
            'discountAmount',
            'couriers',
            'totalWeightGrams'
        ));
    }

    /**
     * Process checkout and create order atomically.
     */
    public function process(CheckoutRequest $request): RedirectResponse
    {
        $user = $request->user();
        $cart = $this->cartService->getCart($user);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong.');
        }

        $checkoutData = $request->validated();
        if (session('applied_coupon') && empty($checkoutData['coupon_code'])) {
            $checkoutData['coupon_code'] = session('applied_coupon');
        }

        $order = $this->orderService->createOrder($user, $cart, $checkoutData);

        // Hapus session kupon setelah berhasil diorder
        session()->forget('applied_coupon');

        // Create payment transaction via Midtrans
        $paymentData = [
            'order_id' => $order->order_number,
            'amount' => $order->total_amount,
            'customer_name' => $checkoutData['recipient_name'],
            'customer_email' => $user->email,
            'customer_phone' => $checkoutData['phone_number'],
            'items' => $order->items->map(function ($item) {
                return [
                    'id' => $item->sku,
                    'price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'name' => $item->product_name . ' - ' . $item->variant_name,
                ];
            })->toArray(),
        ];

        $paymentResult = $this->midtransService->createPayment($paymentData);

        if (!$paymentResult['success']) {
            return redirect()->route('customer.orders.show', $order->order_number)
                ->with('error', 'Gagal membuat pembayaran: ' . $paymentResult['error']);
        }

        // Update payment record with gateway transaction ID
        $order->payment->update([
            'gateway_transaction_id' => $order->order_number,
            'gateway_status' => 'pending',
            'payload' => $paymentResult,
        ]);

        // Redirect to Midtrans payment page
        return redirect()->away($paymentResult['redirect_url']);
    }
}

