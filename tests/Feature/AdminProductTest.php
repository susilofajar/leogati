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
     * Test admin can create product with uploaded images.
     */
    public function test_admin_can_create_product_with_images(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $admin = User::where('email', 'admin@leogati.store')->first();
        $category = Category::first();
        $brand = Brand::first();

        $file1 = \Illuminate\Http\UploadedFile::fake()->image('pc-1.jpg');
        $file2 = \Illuminate\Http\UploadedFile::fake()->image('pc-2.jpg');

        $response = $this->actingAs($admin)->post('/admin/produk', [
            'name' => 'Custom Gaming PC RTX 4090',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'short_description' => 'PC Gaming Monster',
            'description' => 'Spesifikasi dewa',
            'warranty_period_months' => 36,
            'status' => 'active',
            'sku' => 'PC-RTX4090-ULTRA',
            'price' => 65000000,
            'cost_price' => 55000000,
            'stock' => 3,
            'weight_grams' => 15000,
            'images' => [$file1, $file2],
            'primary_image_index' => 0,
        ]);

        $response->assertRedirect(route('admin.produk.index'));
        
        $product = Product::where('slug', 'custom-gaming-pc-rtx-4090')->first();
        $this->assertNotNull($product);
        $this->assertCount(2, $product->images);
        $this->assertTrue($product->primaryImage->is_primary);
    }

    /**
     * Test admin can create category with custom icon.
     */
    public function test_admin_can_create_and_update_category_with_icon(): void
    {
        $admin = User::where('email', 'admin@leogati.store')->first();

        // 1. Create Category with icon
        $response = $this->actingAs($admin)->post('/admin/kategori', [
            'name' => 'Perangkat Gaming Sultan',
            'description' => 'Kategori peripheral gaming level turnamen',
            'icon' => 'gamepad',
            'sort_order' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.kategori.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Perangkat Gaming Sultan',
            'icon' => 'gamepad',
        ]);

        $category = Category::where('name', 'Perangkat Gaming Sultan')->first();
        $this->assertStringContainsString('<svg', $category->icon_svg);

        // 2. Update Category with another icon
        $updateResponse = $this->actingAs($admin)->put('/admin/kategori/' . $category->id, [
            'name' => 'Perangkat Gaming Sultan (Pro)',
            'icon' => 'headphones',
            'sort_order' => 2,
            'is_active' => 1,
        ]);

        $updateResponse->assertRedirect(route('admin.kategori.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'icon' => 'headphones',
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
