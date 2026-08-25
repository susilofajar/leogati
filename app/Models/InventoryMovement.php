<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'warehouse_id',
        'type',
        'quantity_change',
        'quantity_before',
        'quantity_after',
        'reference_type',
        'reference_id',
        'notes',
        'performed_by',
    ];

    protected $casts = [
        'quantity_change' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
    ];

    // Label tipe mutasi dalam Bahasa Indonesia
    public const TYPE_LABELS = [
        'purchase'    => 'Penerimaan Barang',
        'sale'        => 'Penjualan',
        'return'      => 'Pengembalian',
        'adjustment'  => 'Penyesuaian Manual',
        'transfer'    => 'Transfer Gudang',
        'damage'      => 'Barang Rusak',
        'reservation' => 'Reservasi Pesanan',
        'release'     => 'Pelepasan Reservasi',
    ];

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? $this->type;
    }

    public function getIsPositiveAttribute(): bool
    {
        return $this->quantity_change > 0;
    }
}
