<?php

namespace Tests\Feature;

use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogStorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RoleAndPermissionSeeder::class,
            CatalogBaseSeeder::class,
            ProductCatalogSeeder::class,
        ]);
    }

    /**
     * Test catalog index page loads successfully.
     */
    public function test_catalog_index_page_can_be_rendered(): void
    {
        $response = $this->get('/produk');

        $response->assertStatus(200);
        $response->assertSee('Katalog Produk Teknologi');
        $response->assertSee('ASUS ROG Strix SCAR 16');
        $response->assertSee('Intel Core i7-14700K');
        $response->assertSee('Filter Produk');
    }

    /**
     * Test searching by keyword.
     */
    public function test_catalog_search_filters_products(): void
    {
        $response = $this->get('/produk?q=Ryzen');

        $response->assertStatus(200);
        $response->assertSee('AMD Ryzen 7 7800X3D');
        $response->assertDontSee('ASUS ROG Strix SCAR 16');
    }

    /**
     * Test filtering by category.
     */
    public function test_catalog_category_filter(): void
    {
        $response = $this->get('/produk?kategori=laptop');

        $response->assertStatus(200);
        $response->assertSee('ASUS ROG Strix SCAR 16');
        $response->assertSee('Lenovo Legion Pro 5');
        $response->assertDontSee('Prosesor Intel Core i7-14700K');
    }

    /**
     * Test filtering by brand.
     */
    public function test_catalog_brand_filter(): void
    {
        $response = $this->get('/produk?merek=samsung');

        $response->assertStatus(200);
        $response->assertSee('Samsung 990 PRO');
        $response->assertSee('Samsung Odyssey OLED G8');
        $response->assertDontSee('Lenovo Legion Pro 5');
    }

    /**
     * Test product detail page loads with variants and structured specs.
     */
    public function test_product_detail_page_renders_with_specs_and_variants(): void
    {
        $response = $this->get('/produk/asus-rog-strix-scar-16-2026-g634jz');

        $response->assertStatus(200);
        $response->assertSee('ASUS ROG Strix SCAR 16 (2026) G634JZ');
        $response->assertSee('Spesifikasi Teknis Lengkap');
        $response->assertSee('Garansi Resmi Distributor 24 Bulan');
        $response->assertSee('ROG-SCAR16-32-1TB');
        $response->assertSee('32GB RAM / 1TB SSD');
        $response->assertSee('64GB RAM / 2TB SSD');
        $response->assertSee('Prosesor (CPU)');
        $response->assertSee('Soket CPU');
    }

    /**
     * Test category short url redirects.
     */
    public function test_category_url_redirects_to_catalog(): void
    {
        $response = $this->get('/kategori/laptop');

        $response->assertRedirect(route('products.index', ['kategori' => 'laptop']));
    }

    /**
     * Test brand short url redirects.
     */
    public function test_brand_url_redirects_to_catalog(): void
    {
        $response = $this->get('/merek/asus');

        $response->assertRedirect(route('products.index', ['merek' => 'asus']));
    }
}
