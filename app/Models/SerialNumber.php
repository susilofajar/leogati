<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SerialNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'product_variant_id',
        'warehouse_id',
        'purchase_order_id',
        'order_item_id',
        'customer_id',
        'status',
        'purchased_at',
        'sold_at',
        'warranty_expires_at',
        'notes',
    ];

    protected $casts = [
        'purchased_at'       => 'date',
        'sold_at'            => 'date',
        'warranty_expires_at'=> 'date',
    ];

    public const STATUS_LABELS = [
        'available' => 'Tersedia',
        'reserved'  => 'Direservasi',
        'sold'      => 'Terjual',
        'returned'  => 'Dikembalikan',
        'damaged'   => 'Rusak',
        'warranty'  => 'Klaim Garansi',
    ];

    public const STATUS_COLORS = [
        'available' => 'success',
        'reserved'  => 'warning',
        'sold'      => 'primary',
        'returned'  => 'secondary',
        'damaged'   => 'danger',
        'warranty'  => 'info',
    ];

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function isUnderWarranty(): bool
    {
        return $this->status === 'sold'
            && $this->warranty_expires_at
            && $this->warranty_expires_at->isFuture();
    }

    public function warrantyClaims(): HasMany
    {
        return $this->hasMany(WarrantyClaim::class);
    }

    /**
     * Apakah serial number ini memiliki klaim garansi aktif (belum selesai).
     */
    public function hasActiveClaim(): bool
    {
        return $this->warrantyClaims()
            ->whereNotIn('status', WarrantyClaim::TERMINAL_STATUSES)
            ->exists();
    }
}
