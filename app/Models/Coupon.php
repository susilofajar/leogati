<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_purchase_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'value'               => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit'         => 'integer',
        'used_count'          => 'integer',
        'start_date'          => 'datetime',
        'end_date'            => 'datetime',
        'is_active'           => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Hitung besaran diskon berdasarkan subtotal belanja.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_purchase_amount) {
            return 0.0;
        }

        if ($this->type === 'percent') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = (float) $this->max_discount_amount;
            }
            return (float) min($discount, $subtotal);
        }

        // Tipe fixed (rupiah)
        return (float) min($this->value, $subtotal);
    }

    /**
     * Validasi apakah kupon saat ini aktif dan dapat digunakan.
     */
    public function isValid(?float $subtotal = null): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->start_date && $now->isBefore($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->isAfter($this->end_date)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($subtotal !== null && $subtotal < $this->min_purchase_amount) {
            return false;
        }

        return true;
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'percent' ? "Diskon {$this->value}%" : "Potongan " . rupiah($this->value);
    }
}
