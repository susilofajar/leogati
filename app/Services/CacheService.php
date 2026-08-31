<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\HeroBanner;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    public const TTL_SHORT = 300;       // 5 Menit
    public const TTL_MEDIUM = 3600;     // 1 Jam
    public const TTL_LONG = 86400;      // 24 Jam

    public const KEY_ACTIVE_CATEGORIES = 'leogati:categories:active_tree';
    public const KEY_ACTIVE_BRANDS = 'leogati:brands:active_list';
    public const KEY_FEATURED_PRODUCTS = 'leogati:products:featured_list';
    public const KEY_PC_BUILDER_COMPONENTS = 'leogati:pc_builder:components';
    public const KEY_HERO_BANNERS = 'leogati:hero_banners:active_list';

    /**
     * Ambil daftar kategori aktif dengan cache yang aman.
     */
    public function getCachedCategories(): Collection
    {
        $cached = Cache::get(self::KEY_ACTIVE_CATEGORIES);
        if ($cached instanceof Collection) {
            return $cached;
        }

        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        Cache::put(self::KEY_ACTIVE_CATEGORIES, $categories, self::TTL_MEDIUM);

        return $categories;
    }

    /**
     * Ambil daftar merek aktif dengan cache yang aman.
     */
    public function getCachedBrands(): Collection
    {
        $cached = Cache::get(self::KEY_ACTIVE_BRANDS);
        if ($cached instanceof Collection) {
            return $cached;
        }

        $brands = Brand::where('is_active', true)
            ->withCount(['products' => function ($q) {
                $q->where('status', 'active');
            }])
            ->orderBy('name', 'asc')
            ->get();

        Cache::put(self::KEY_ACTIVE_BRANDS, $brands, self::TTL_MEDIUM);

        return $brands;
    }

    /**
     * Ambil produk unggulan beranda dengan cache yang aman.
     */
    public function getCachedFeaturedProducts(int $limit = 8): Collection
    {
        $key = self::KEY_FEATURED_PRODUCTS . ":limit_{$limit}";
        $cached = Cache::get($key);
        if ($cached instanceof Collection) {
            return $cached;
        }

        $products = Product::active()
            ->where('is_featured', true)
            ->with(['brand', 'category', 'variants', 'images'])
            ->latest()
            ->take($limit)
            ->get();

        Cache::put($key, $products, self::TTL_SHORT);

        return $products;
    }

    /**
     * Ambil daftar banner hero aktif dengan cache yang aman.
     */
    public function getCachedHeroBanners(): Collection
    {
        $cached = Cache::get(self::KEY_HERO_BANNERS);
        if ($cached instanceof Collection) {
            return $cached;
        }

        $banners = HeroBanner::active()
            ->ordered()
            ->get();

        Cache::put(self::KEY_HERO_BANNERS, $banners, self::TTL_MEDIUM);

        return $banners;
    }

    /**
     * Hapus cache banner hero.
     */
    public function flushHeroBannerCache(): void
    {
        Cache::forget(self::KEY_HERO_BANNERS);
    }

    /**
     * Hapus cache katalog saat ada modifikasi data oleh Admin/Sistem.
     */
    public function flushCatalogCache(): void
    {
        Cache::forget(self::KEY_ACTIVE_CATEGORIES);
        Cache::forget(self::KEY_ACTIVE_BRANDS);
        Cache::forget(self::KEY_PC_BUILDER_COMPONENTS);
        Cache::forget(self::KEY_HERO_BANNERS);
        Cache::forget(self::KEY_FEATURED_PRODUCTS . ':limit_8');
        Cache::forget(self::KEY_FEATURED_PRODUCTS . ':limit_10');
        Cache::forget(self::KEY_FEATURED_PRODUCTS . ':limit_12');
    }
}
