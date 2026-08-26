<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SeoAndOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic category, brand, and product
        $category = Category::create([
            'name' => 'Komponen PC',
            'slug' => 'komponen-pc',
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'ASUS ROG',
            'slug' => 'asus-rog',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'ASUS ROG Strix RTX 4080',
            'slug' => 'asus-rog-strix-rtx-4080',
            'short_description' => 'Kartu grafis gaming performa tinggi bergaransi resmi.',
            'description' => 'Detail deskripsi lengkap kartu grafis RTX 4080.',
            'status' => 'active',
            'is_featured' => true,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Default',
            'sku' => 'ROG-RTX4080-16G',
            'price' => 21500000,
            'stock' => 10,
            'is_default' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Test XML Sitemap endpoint generation and format.
     */
    public function test_sitemap_xml_returns_valid_xml_response_with_links(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));

        $content = $response->getContent();
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $content);
        $this->assertStringContainsString('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', $content);
        $this->assertStringContainsString('/produk', $content);
        $this->assertStringContainsString('/pc-builder', $content);
        $this->assertStringContainsString('/kategori/komponen-pc', $content);
        $this->assertStringContainsString('/merek/asus-rog', $content);
        $this->assertStringContainsString('/produk/asus-rog-strix-rtx-4080', $content);
    }

    /**
     * Test robots.txt endpoint generation and directives.
     */
    public function test_robots_txt_returns_text_with_sitemap_and_disallow_rules(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));

        $content = $response->getContent();
        $this->assertStringContainsString('User-agent: *', $content);
        $this->assertStringContainsString('Disallow: /admin/', $content);
        $this->assertStringContainsString('Disallow: /akun/', $content);
        $this->assertStringContainsString('Disallow: /checkout/', $content);
        $this->assertStringContainsString('Sitemap:', $content);
        $this->assertStringContainsString('/sitemap.xml', $content);
    }

    /**
     * Test product detail page contains OpenGraph and Schema.org JSON-LD structured data.
     */
    public function test_product_detail_page_contains_schema_and_opengraph_tags(): void
    {
        $response = $this->get('/produk/asus-rog-strix-rtx-4080');

        $response->assertStatus(200);
        $response->assertSee('<meta property="og:type" content="product">', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type": "Product"', false);
        $response->assertSee('"name": "ASUS ROG Strix RTX 4080"', false);
        $response->assertSee('"sku": "ROG-RTX4080-16G"', false);
        $response->assertSee('"@type": "AggregateOffer"', false);
        $response->assertSee('"priceCurrency": "IDR"', false);
        $response->assertSee('"@type": "BreadcrumbList"', false);
    }

    /**
     * Test CacheService caching and invalidation behavior.
     */
    public function test_cache_service_caches_and_flushes_catalog(): void
    {
        $cacheService = app(CacheService::class);

        // First call caches data
        $categories = $cacheService->getCachedCategories();
        $this->assertCount(1, $categories);
        $this->assertTrue(Cache::has(CacheService::KEY_ACTIVE_CATEGORIES));

        $brands = $cacheService->getCachedBrands();
        $this->assertCount(1, $brands);
        $this->assertTrue(Cache::has(CacheService::KEY_ACTIVE_BRANDS));

        $featured = $cacheService->getCachedFeaturedProducts(8);
        $this->assertCount(1, $featured);

        // Flush cache
        $cacheService->flushCatalogCache();
        $this->assertFalse(Cache::has(CacheService::KEY_ACTIVE_CATEGORIES));
        $this->assertFalse(Cache::has(CacheService::KEY_ACTIVE_BRANDS));
    }
}
