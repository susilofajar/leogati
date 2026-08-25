<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComparisonTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product1;
    protected Product $product2;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name'      => 'Laptop Gaming',
            'slug'      => 'laptop-gaming',
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name'      => 'ASUS ROG',
            'slug'      => 'asus-rog',
            'is_active' => true,
        ]);

        $this->product1 = Product::create([
            'category_id'            => $category->id,
            'brand_id'               => $brand->id,
            'name'                   => 'ROG Zephyrus G14',
            'slug'                   => 'rog-zephyrus-g14',
            'short_description'      => 'Laptop gaming 14 inch.',
            'warranty_period_months' => 24,
            'is_active'              => true,
        ]);

        ProductVariant::create([
            'product_id' => $this->product1->id,
            'sku'        => 'ROG-G14-001',
            'name'       => 'Default',
            'price'      => 24999000,
            'stock'      => 5,
            'is_active'  => true,
            'is_default' => true,
        ]);

        $this->product2 = Product::create([
            'category_id'            => $category->id,
            'brand_id'               => $brand->id,
            'name'                   => 'ROG Strix SCAR 16',
            'slug'                   => 'rog-strix-scar-16',
            'short_description'      => 'Laptop gaming 16 inch.',
            'warranty_period_months' => 24,
            'is_active'              => true,
        ]);

        ProductVariant::create([
            'product_id' => $this->product2->id,
            'sku'        => 'ROG-SCAR-001',
            'name'       => 'Default',
            'price'      => 38999000,
            'stock'      => 3,
            'is_active'  => true,
            'is_default' => true,
        ]);
    }

    public function test_comparison_page_can_be_rendered(): void
    {
        $response = $this->get(route('comparison.index'));
        $response->assertStatus(200);
        $response->assertSee('Perbandingan Spesifikasi Produk');
    }

    public function test_can_add_products_to_comparison(): void
    {
        $response = $this->post(route('comparison.add'), [
            'product_id' => $this->product1->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals([$this->product1->id], session('compare_products'));

        $response2 = $this->post(route('comparison.add'), [
            'product_id' => $this->product2->id,
        ]);

        $response2->assertSessionHas('success');
        $this->assertEquals([$this->product1->id, $this->product2->id], session('compare_products'));
    }

    public function test_comparison_renders_added_products(): void
    {
        $response = $this->withSession([
            'compare_products' => [$this->product1->id, $this->product2->id]
        ])->get(route('comparison.index'));

        $response->assertStatus(200);
        $response->assertSee('ROG Zephyrus G14');
        $response->assertSee('ROG Strix SCAR 16');
        $response->assertSee(rupiah(24999000));
        $response->assertSee(rupiah(38999000));
    }

    public function test_can_remove_product_from_comparison(): void
    {
        $response = $this->withSession([
            'compare_products' => [$this->product1->id, $this->product2->id]
        ])->delete(route('comparison.remove', $this->product1->id));

        $response->assertSessionHas('success');
        $this->assertEquals([$this->product2->id], session('compare_products'));
    }

    public function test_cannot_add_more_than_4_products(): void
    {
        $cat = Category::first();
        $brand = Brand::first();

        $p3 = Product::create(['category_id' => $cat->id, 'brand_id' => $brand->id, 'name' => 'P3', 'slug' => 'p-3', 'warranty_period_months' => 12]);
        $p4 = Product::create(['category_id' => $cat->id, 'brand_id' => $brand->id, 'name' => 'P4', 'slug' => 'p-4', 'warranty_period_months' => 12]);
        $p5 = Product::create(['category_id' => $cat->id, 'brand_id' => $brand->id, 'name' => 'P5', 'slug' => 'p-5', 'warranty_period_months' => 12]);

        $response = $this->withSession([
            'compare_products' => [$this->product1->id, $this->product2->id, $p3->id, $p4->id]
        ])->post(route('comparison.add'), [
            'product_id' => $p5->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertCount(4, session('compare_products'));
    }
}
