<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductTest extends TestCase
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
     * Test guest cannot access admin product management.
     */
    public function test_guest_cannot_access_admin_products(): void
    {
        $response = $this->get('/admin/produk');

        $response->assertRedirect(route('login'));
    }

    /**
     * Test admin can view admin product list.
     */
    public function test_admin_can_view_product_list(): void
    {
        $admin = User::where('email', 'admin@leogati.store')->first();

        $response = $this->actingAs($admin)->get('/admin/produk');

        $response->assertStatus(200);
        $response->assertSee('Daftar Produk', false);
        $response->assertSee('ASUS ROG Strix SCAR 16', false);
    }

    /**
     * Test admin can create product.
     */
    public function test_admin_can_create_new_product(): void
    {
        $admin = User::where('email', 'admin@leogati.store')->first();
        $category = Category::first();
        $brand = Brand::first();

        $response = $this->actingAs($admin)->post('/admin/produk', [
            'name' => 'Custom Gaming PC Ryzen 9 7950X',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'short_description' => 'PC Rakitan Super Kencang',
            'description' => 'Deskripsi lengkap perakitan PC',
            'warranty_period_months' => 24,
            'status' => 'active',
            'sku' => 'PC-RYZEN9-7950X',
            'price' => 35000000,
            'cost_price' => 30000000,
            'stock' => 5,
            'weight_grams' => 12000,
        ]);

        $response->assertRedirect(route('admin.produk.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Custom Gaming PC Ryzen 9 7950X',
            'slug' => 'custom-gaming-pc-ryzen-9-7950x',
        ]);
        $this->assertDatabaseHas('product_variants', [
            'sku' => 'PC-RYZEN9-7950X',
            'price' => 35000000,
        ]);
    }

    /**
     * Test admin can update existing product.
     */
    public function test_admin_can_update_product(): void
    {
        $admin = User::where('email', 'admin@leogati.store')->first();
        $product = Product::first();

        $response = $this->actingAs($admin)->put('/admin/produk/' . $product->id, [
            'name' => 'ASUS ROG Strix SCAR 16 (Updated 2026)',
            'category_id' => $product->category_id,
            'brand_id' => $product->brand_id,
            'short_description' => 'Updated short description',
            'description' => 'Updated long description',
            'warranty_period_months' => 36,
            'status' => 'active',
            'sku' => $product->defaultVariant->sku,
            'price' => 54000000,
            'cost_price' => 48000000,
            'stock' => 25,
            'weight_grams' => 2700,
        ]);

        $response->assertRedirect(route('admin.produk.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'ASUS ROG Strix SCAR 16 (Updated 2026)',
            'warranty_period_months' => 36,
        ]);
    }

    /**
     * Test admin can delete product.
     */
    public function test_admin_can_delete_product(): void
    {
        $admin = User::where('email', 'admin@leogati.store')->first();
        $product = Product::first();
        $productId = $product->id;

        $response = $this->actingAs($admin)->delete('/admin/produk/' . $productId);

        $response->assertRedirect(route('admin.produk.index'));
        $this->assertDatabaseMissing('products', [
            'id' => $productId,
        ]);
    }
}
