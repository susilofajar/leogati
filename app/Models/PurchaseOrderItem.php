<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_variant_id',
        'quantity_ordered',
        'quantity_received',
        'unit_cost',
        'notes',
    ];

    protected $casts = [
        'quantity_ordered'  => 'integer',
        'quantity_received' => 'integer',
        'unit_cost'         => 'decimal:2',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function serialNumbers(): HasMany
    {
        return $this->hasMany(SerialNumber::class, 'purchase_order_id', 'purchase_order_id')
                    ->where('product_variant_id', $this->product_variant_id);
    }

    public function getSubtotalAttribute(): float
    {
        return (float) ($this->quantity_ordered * $this->unit_cost);
    }

    public function getRemainingQuantityAttribute(): int
    {
        return max(0, $this->quantity_ordered - $this->quantity_received);
    }
}
