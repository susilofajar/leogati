<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'warehouse_id',
        'created_by',
        'status',
        'total_amount',
        'expected_at',
        'received_at',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'expected_at'  => 'date',
        'received_at'  => 'date',
    ];

    public const STATUS_LABELS = [
        'draft'     => 'Draft',
        'sent'      => 'Dikirim ke Supplier',
        'partial'   => 'Diterima Sebagian',
        'received'  => 'Diterima Lengkap',
        'cancelled' => 'Dibatalkan',
    ];

    public const STATUS_COLORS = [
        'draft'     => 'secondary',
        'sent'      => 'info',
        'partial'   => 'warning',
        'received'  => 'success',
        'cancelled' => 'danger',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function serialNumbers(): HasMany
    {
        return $this->hasMany(SerialNumber::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    /**
     * Generate nomor PO unik: PO-YYYYMMDD-XXXX
     */
    public static function generatePoNumber(): string
    {
        $datePrefix = 'PO-' . Carbon::now()->format('Ymd');
        $random = strtoupper(Str::random(4));
        $poNumber = "{$datePrefix}-{$random}";

        while (static::where('po_number', $poNumber)->exists()) {
            $random = strtoupper(Str::random(4));
            $poNumber = "{$datePrefix}-{$random}";
        }

        return $poNumber;
    }
}
