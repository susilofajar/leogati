<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SerialNumber;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    /**
     * Tambah atau kurangi stok varian di gudang.
     * Selalu mencatat InventoryMovement. Update cache di product_variants.stock.
     *
     * @param  ProductVariant  $variant
     * @param  Warehouse       $warehouse
     * @param  int             $quantityChange   Positif = masuk, Negatif = keluar
     * @param  string          $type             Jenis mutasi (sale, purchase, adjustment, dll)
     * @param  object|null     $reference        Model referensi (Order, PurchaseOrder, dll)
     * @param  string|null     $notes
     * @param  User|null       $performedBy
     */
    public function adjustStock(
        ProductVariant $variant,
        Warehouse $warehouse,
        int $quantityChange,
        string $type,
        ?object $reference = null,
        ?string $notes = null,
        ?User $performedBy = null
    ): InventoryMovement {
        // Dapatkan atau buat record inventory
        $inventory = Inventory::where('product_variant_id', $variant->id)
            ->where('warehouse_id', $warehouse->id)
            ->first();

        if (! $inventory) {
            $hasAny = Inventory::where('product_variant_id', $variant->id)->exists();
            $initialQty = $hasAny ? 0 : $variant->stock;
            $inventory = Inventory::create([
                'product_variant_id' => $variant->id,
                'warehouse_id'       => $warehouse->id,
                'quantity'           => $initialQty,
                'reserved_quantity'  => 0,
            ]);
        }

        $quantityBefore = $inventory->quantity;
        $quantityAfter  = $quantityBefore + $quantityChange;

        if ($quantityAfter < 0) {
            throw ValidationException::withMessages([
                'stock' => "Stok tidak mencukupi untuk '{$variant->product->name} - {$variant->name}'. " .
                           "Stok saat ini: {$quantityBefore}, dibutuhkan: " . abs($quantityChange) . ".",
            ]);
        }

        // Update tabel inventory
        $inventory->update(['quantity' => $quantityAfter]);

        // Update cache cepat di product_variants.stock (total semua gudang)
        $totalStock = (int) Inventory::where('product_variant_id', $variant->id)->sum('quantity');
        $variant->update(['stock' => $totalStock]);

        // Catat mutasi
        $movement = InventoryMovement::create([
            'product_variant_id' => $variant->id,
            'warehouse_id'       => $warehouse->id,
            'type'               => $type,
            'quantity_change'    => $quantityChange,
            'quantity_before'    => $quantityBefore,
            'quantity_after'     => $quantityAfter,
            'reference_type'     => $reference ? get_class($reference) : null,
            'reference_id'       => $reference?->id,
            'notes'              => $notes,
            'performed_by'       => $performedBy?->id,
        ]);

        // Audit Log for manual stock adjustments
        if ($type === 'adjustment') {
            AuditLogService::log(
                action: 'stock_adjusted',
                targetType: 'ProductVariant',
                targetId: $variant->id,
                payload: [
                    'product_name' => $variant->product?->name,
                    'variant_name' => $variant->name,
                    'sku' => $variant->sku,
                    'quantity_change' => $quantityChange,
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $quantityAfter,
                    'warehouse' => $warehouse->name,
                    'notes' => $notes,
                ],
                userId: $performedBy?->id,
                userName: $performedBy?->name
            );
        }

        // Notify admins if stock is critically low (<= 5)
        if ($totalStock <= 5 && $quantityChange < 0) {
            try {
                $admins = User::whereHas('roles', fn($q) => $q->whereIn('name', ['super_admin', 'admin', 'warehouse_staff']))->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\LowStockAlertNotification($variant));
                }
            } catch (\Throwable $e) {
                // Ignore notification failure
            }
        }

        return $movement;
    }

    /**
     * Terima barang dari Purchase Order.
     * Menambah stok, mencatat mutasi 'purchase', dan opsional meregistrasi nomor seri.
     *
     * @param  PurchaseOrderItem  $poItem
     * @param  int                $quantityReceived
     * @param  array              $serialNumbers    Array nomor seri (opsional untuk varian non-serialized)
     * @param  int                $warrantyMonths   Masa garansi dalam bulan
     * @param  User               $performedBy
     */
    public function receiveGoods(
        PurchaseOrderItem $poItem,
        int $quantityReceived,
        array $serialNumbers,
        int $warrantyMonths,
        User $performedBy
    ): void {
        DB::transaction(function () use ($poItem, $quantityReceived, $serialNumbers, $warrantyMonths, $performedBy) {
            $variant       = $poItem->productVariant;
            $purchaseOrder = $poItem->purchaseOrder;
            $warehouse     = $purchaseOrder->warehouse;

            // Validasi jumlah yang diterima
            $remaining = $poItem->quantity_ordered - $poItem->quantity_received;
            if ($quantityReceived > $remaining) {
                throw ValidationException::withMessages([
                    'quantity' => "Jumlah yang diterima ({$quantityReceived}) melebihi sisa yang belum diterima ({$remaining}).",
                ]);
            }

            // Validasi nomor seri jika varian bertipe serialized
            if ($variant->is_serialized) {
                if (count($serialNumbers) !== $quantityReceived) {
                    throw ValidationException::withMessages([
                        'serial_numbers' => "Jumlah nomor seri (" . count($serialNumbers) . ") harus sesuai dengan jumlah barang diterima ({$quantityReceived}).",
                    ]);
                }

                // Cek duplikasi nomor seri
                foreach ($serialNumbers as $sn) {
                    if (SerialNumber::where('serial_number', $sn)->exists()) {
                        throw ValidationException::withMessages([
                            'serial_numbers' => "Nomor seri '{$sn}' sudah terdaftar di sistem.",
                        ]);
                    }
                }

                // Registrasi setiap nomor seri
                $today          = Carbon::today();
                $warrantyExpiry = $warrantyMonths > 0 ? $today->copy()->addMonths($warrantyMonths) : null;

                foreach ($serialNumbers as $sn) {
                    SerialNumber::create([
                        'serial_number'       => $sn,
                        'product_variant_id'  => $variant->id,
                        'warehouse_id'        => $warehouse->id,
                        'purchase_order_id'   => $purchaseOrder->id,
                        'status'              => 'available',
                        'purchased_at'        => $today,
                        'warranty_expires_at' => $warrantyExpiry,
                    ]);
                }
            }

            // Tambah stok & catat mutasi
            $this->adjustStock(
                $variant,
                $warehouse,
                $quantityReceived,
                'purchase',
                $purchaseOrder,
                "Penerimaan dari PO #{$purchaseOrder->po_number}",
                $performedBy
            );

            // Update jumlah diterima di PO item
            $poItem->increment('quantity_received', $quantityReceived);

            // Update status PO
            $this->updatePurchaseOrderStatus($purchaseOrder);
        });
    }

    /**
     * Kurangi stok saat pesanan dibuat (dipanggil dari OrderService).
     * Menggunakan gudang default sistem.
     *
     * @param  ProductVariant  $variant
     * @param  int             $quantity
     * @param  Order           $order
     * @param  User|null       $performedBy
     */
    public function deductSaleStock(
        ProductVariant $variant,
        int $quantity,
        Order $order,
        ?User $performedBy = null
    ): InventoryMovement {
        $warehouse = Warehouse::default();

        if (! $warehouse) {
            // Fallback: buat gerakan stok tanpa gudang spesifik
            // menggunakan inventory record pertama yang tersedia
            $inventory = Inventory::where('product_variant_id', $variant->id)
                                  ->where('quantity', '>=', $quantity)
                                  ->first();

            if (! $inventory) {
                // Cukup langsung decrement di product_variants.stock (backward compat)
                $variant->decrement('stock', $quantity);
                return new InventoryMovement(); // Return empty movement
            }

            $warehouse = $inventory->warehouse;
        }

        return $this->adjustStock(
            $variant,
            $warehouse,
            -$quantity,
            'sale',
            $order,
            "Penjualan — Pesanan #{$order->order_number}",
            $performedBy
        );
    }

    /**
     * Update status Purchase Order berdasarkan quantity_received semua item.
     */
    protected function updatePurchaseOrderStatus(PurchaseOrder $po): void
    {
        $po->refresh();
        $items = $po->items;

        $allReceived  = $items->every(fn($i) => $i->quantity_received >= $i->quantity_ordered);
        $anyReceived  = $items->some(fn($i) => $i->quantity_received > 0);

        if ($allReceived) {
            $po->update(['status' => 'received', 'received_at' => Carbon::today()]);
        } elseif ($anyReceived) {
            $po->update(['status' => 'partial']);
        }
    }
}
