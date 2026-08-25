<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class)->with('variant.product');
    }

    /**
     * Get subtotal of all items in cart based on live database variant prices.
     */
    public function getSubtotalAttribute(): float
    {
        return (float) $this->items->sum(function ($item) {
            return $item->variant ? ($item->variant->price * $item->quantity) : 0;
        });
    }

    /**
     * Get total quantity of items in cart.
     */
    public function getTotalQuantityAttribute(): int
    {
        return (int) $this->items->sum('quantity');
    }

    /**
     * Get total weight in grams of all items in cart.
     */
    public function getTotalWeightAttribute(): int
    {
        return (int) $this->items->sum(function ($item) {
            return $item->variant ? ($item->variant->weight_grams * $item->quantity) : 0;
        });
    }
}
