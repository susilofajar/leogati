<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductVideoTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;
    protected Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Admin', 'description' => 'Administrator Toko']
        );

        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($adminRole);

        $this->category = Category::create([
            'name'      => 'Laptop',
            'slug'      => 'laptop',
            'is_active' => true,
        ]);

        $this->brand = Brand::create([
            'name'      => 'ASUS',
            'slug'      => 'asus',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_product_with_optional_video(): void
    {
        Storage::fake('public');

        $videoFile = UploadedFile::fake()->create('demo_video.mp4', 5000, 'video/mp4');

        $response = $this->actingAs($this->admin)->post(route('admin.produk.store'), [
            'name'                   => 'ASUS ROG Strix G16 Video Test',
            'category_id'            => $this->category->id,
            'brand_id'               => $this->brand->id,
            'short_description'      => 'Laptop gaming dengan video demo unboxing',
            'description'            => 'Deskripsi lengkap laptop...',
            'warranty_period_months' => 24,
            'status'                 => 'active',
            'sku'                    => 'ROG-G16-VID',
            'price'                  => 25000000,
            'cost_price'             => 20000000,
            'stock'                  => 5,
            'weight_grams'           => 2500,
            'video'                  => $videoFile,
        ]);

        $response->assertRedirect(route('admin.produk.index'));
        $response->assertSessionHas('success');

        $product = Product::where('sku', 'ROG-G16-VID')->orWhere('name', 'ASUS ROG Strix G16 Video Test')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->video_path);
        $this->assertTrue($product->hasVideo());

        $relPath = str_replace('storage/', '', $product->video_path);
        Storage::disk('public')->assertExists($relPath);
    }

    public function test_storefront_renders_video_element_when_product_has_video(): void
    {
        $product = Product::create([
            'category_id'            => $this->category->id,
            'brand_id'               => $this->brand->id,
            'name'                   => 'Laptop with Video',
            'slug'                   => 'laptop-with-video',
            'short_description'      => 'Short description',
            'description'            => 'Full description',
            'video_path'             => 'storage/products/videos/test_video.mp4',
            'warranty_period_months' => 12,
            'status'                 => 'active',
        ]);

        $product->variants()->create([
            'name'         => 'Default',
            'sku'          => 'VID-TEST-001',
            'price'        => 10000000,
            'cost_price'   => 8000000,
            'stock'        => 5,
            'weight_grams' => 1000,
            'is_default'   => true,
            'is_active'    => true,
        ]);

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('videoSrc');
        $response->assertSee('test_video.mp4');
    }

    public function test_admin_can_delete_video_from_product(): void
    {
        Storage::fake('public');

        $videoFile = UploadedFile::fake()->create('demo_video.mp4', 5000, 'video/mp4');
        $path = $videoFile->store('products/videos', 'public');

        $product = Product::create([
            'category_id'            => $this->category->id,
            'brand_id'               => $this->brand->id,
            'name'                   => 'Laptop to Delete Video',
            'slug'                   => 'laptop-to-delete-video',
            'short_description'      => 'Short description',
            'description'            => 'Full description',
            'video_path'             => 'storage/' . $path,
            'warranty_period_months' => 12,
            'status'                 => 'active',
        ]);

        $product->variants()->create([
            'name'         => 'Default',
            'sku'          => 'VID-DEL-001',
            'price'        => 10000000,
            'stock'        => 5,
            'weight_grams' => 1000,
            'is_default'   => true,
            'is_active'    => true,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->put(route('admin.produk.update', $product->id), [
            'name'                   => $product->name,
            'category_id'            => $this->category->id,
            'brand_id'               => $this->brand->id,
            'short_description'      => $product->short_description,
            'description'            => $product->description,
            'warranty_period_months' => 12,
            'status'                 => 'active',
            'sku'                    => 'VID-DEL-001',
            'price'                  => 10000000,
            'stock'                  => 5,
            'weight_grams'           => 1000,
            'delete_video'           => '1',
        ]);

        $response->assertRedirect(route('admin.produk.index'));
        $product->refresh();
        $this->assertNull($product->video_path);
        $this->assertFalse($product->hasVideo());
        Storage::disk('public')->assertMissing($path);
    }
}
