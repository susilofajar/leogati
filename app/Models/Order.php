<?php

namespace App\Models;

use App\Support\Formatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal_amount',
        'shipping_amount',
        'discount_amount',
        'coupon_id',
        'coupon_code',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'shipping_courier',
        'shipping_service',
        'shipping_tracking_number',
        'shipping_address',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'subtotal_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get localized status badge details in Indonesian.
     */
    public function getStatusDetailsAttribute(): array
    {
        return Formatter::orderStatus($this->status);
    }

    /**
     * Get localized payment method name in Indonesian.
     */
    public function getPaymentMethodNameAttribute(): string
    {
        $methods = [
            'bca_va' => 'BCA Virtual Account',
            'mandiri_va' => 'Mandiri Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'qris' => 'QRIS Instant Payment',
            'bank_transfer' => 'Transfer Bank Manual',
        ];

        return $methods[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    /**
     * Get formatted courier name.
     */
    public function getCourierNameAttribute(): string
    {
        $couriers = [
            'jne' => 'JNE Reguler Express',
            'sicepat' => 'SiCepat BEST / GOKIL',
            'jnt' => 'J&T Express Super',
        ];

        return $couriers[$this->shipping_courier] ?? strtoupper($this->shipping_courier ?? 'Kurir Toko');
    }
}
