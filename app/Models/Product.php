<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'short_description',
        'description',
        'warranty_period_months',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'warranty_period_months' => 'integer',
        'is_featured' => 'boolean',
    ];

    /**
     * The category that the product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The brand that the product belongs to.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * The variants for the product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * The default variant for the product.
     */
    public function defaultVariant(): HasOne
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true);
    }

    /**
     * The images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * The primary image for the product.
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * URL Foto Utama Produk atau gambar pertama yang valid.
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->primaryImage && !empty($this->primaryImage->image_path)) {
            return asset($this->primaryImage->image_path);
        }

        $firstImg = $this->relationLoaded('images') ? $this->images->first() : $this->images()->first();
        if ($firstImg && !empty($firstImg->image_path)) {
            return asset($firstImg->image_path);
        }

        return null;
    }

    /**
     * Render thumbnail gambar produk yang seragam di seluruh storefront (Home, Katalog, Keranjang, Komparasi, Checkout, Wishlist).
     */
    public function renderThumbnail(string $class = 'max-h-full max-w-full object-contain', string $iconClass = 'w-7 h-7'): string
    {
        if (!empty($this->image_url)) {
            return '<img src="' . e($this->image_url) . '" alt="' . e($this->name) . '" class="' . $class . '">';
        }

        if ($this->category) {
            return $this->category->renderIcon($iconClass);
        }

        return '<svg class="' . $iconClass . '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>';
    }

    /**
     * The structured specifications for the product.
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)->with('attribute.group');
    }

    /**
     * Scope for active products.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured products.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get min price across active variants.
     */
    public function getMinPriceAttribute(): float
    {
        return (float) ($this->variants()->min('price') ?? 0);
    }

    /**
     * Get total stock across all active variants.
     */
    public function getTotalStockAttribute(): int
    {
        return (int) ($this->variants()->sum('stock') ?? 0);
    }

    /**
     * Check if product is in stock.
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->total_stock > 0;
    }

    /**
     * The approved reviews for the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * All reviews including pending moderation.
     */
    public function allReviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get average rating (1.0 to 5.0).
     */
    public function getAverageRatingAttribute(): float
    {
        return round((float) ($this->reviews()->avg('rating') ?? 5.0), 1);
    }

    /**
     * Get total approved reviews count.
     */
    public function getReviewsCountAttribute(): int
    {
        return (int) $this->reviews()->count();
    }
}
