<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'price',
        'cost_price',
        'stock',
        'weight_grams',
        'dimensions',
        'barcode',
        'is_default',
        'is_active',
        'is_serialized',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'cost_price'    => 'decimal:2',
        'stock'         => 'integer',
        'weight_grams'  => 'integer',
        'is_default'    => 'boolean',
        'is_active'     => 'boolean',
        'is_serialized' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryRecords(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function serialNumbers(): HasMany
    {
        return $this->hasMany(SerialNumber::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Ambil record inventory di gudang tertentu.
     */
    public function inventoryAt(int $warehouseId): ?Inventory
    {
        return $this->inventoryRecords()->where('warehouse_id', $warehouseId)->first();
    }

    /**
     * Hitung total stok di semua gudang.
     */
    public function getTotalStockAttribute(): int
    {
        return (int) $this->inventoryRecords()->sum('quantity');
    }
}
