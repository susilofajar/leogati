<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'is_verified_purchase',
        'is_approved',
        'admin_reply',
        'admin_replied_at',
    ];

    protected $casts = [
        'rating'               => 'integer',
        'is_verified_purchase' => 'boolean',
        'is_approved'          => 'boolean',
        'admin_replied_at'     => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope ulasan yang disetujui (publik).
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
