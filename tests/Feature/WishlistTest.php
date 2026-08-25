<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected Product $product;
    protected ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name'         => 'customer',
            'display_name' => 'Pelanggan',
        ]);

        $this->customer = User::factory()->create([
            'email' => 'customer@example.com',
        ]);
        $this->customer->roles()->attach($role);

        $category = Category::create([
            'name'        => 'Laptop Gaming',
            'slug'        => 'laptop-gaming',
            'is_active'   => true,
        ]);

        $brand = Brand::create([
            'name'        => 'ASUS ROG',
            'slug'        => 'asus-rog',
            'is_active'   => true,
        ]);

        $this->product = Product::create([
            'category_id'            => $category->id,
            'brand_id'               => $brand->id,
            'name'                   => 'ROG Zephyrus G14',
            'slug'                   => 'rog-zephyrus-g14',
            'short_description'      => 'Laptop gaming ultra-ringkas.',
            'warranty_period_months' => 24,
            'is_active'              => true,
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'sku'        => 'ROG-G14-001',
            'name'       => 'Ryzen 9 / 16GB / 1TB RTX 4060',
            'price'      => 24999000,
            'stock'      => 5,
            'is_active'  => true,
            'is_default' => true,
        ]);
    }

    public function test_guest_is_redirected_when_accessing_wishlist_page(): void
    {
        $response = $this->get(route('customer.wishlist.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_can_render_wishlist_page(): void
    {
        Wishlist::create([
            'user_id'    => $this->customer->id,
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->customer)->get(route('customer.wishlist.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Keinginan Saya');
        $response->assertSee('ROG Zephyrus G14');
    }

    public function test_customer_can_toggle_add_and_remove_wishlist(): void
    {
        // 1. Add to wishlist
        $response = $this->actingAs($this->customer)->post(route('wishlist.toggle'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('wishlists', [
            'user_id'    => $this->customer->id,
            'product_id' => $this->product->id,
        ]);

        // 2. Toggle again to remove
        $response2 = $this->actingAs($this->customer)->post(route('wishlist.toggle'), [
            'product_id' => $this->product->id,
        ]);

        $response2->assertSessionHas('success');
        $this->assertDatabaseMissing('wishlists', [
            'user_id'    => $this->customer->id,
            'product_id' => $this->product->id,
        ]);
    }

    public function test_customer_can_toggle_wishlist_via_ajax(): void
    {
        $response = $this->actingAs($this->customer)->postJson(route('wishlist.toggle'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'added'  => true,
            'count'  => 1,
        ]);
    }

    public function test_customer_can_delete_wishlist_item(): void
    {
        $wishlist = Wishlist::create([
            'user_id'    => $this->customer->id,
            'product_id' => $this->product->id,
        ]);

        $response = $this->actingAs($this->customer)->delete(route('wishlist.destroy', $wishlist->id));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('wishlists', [
            'id' => $wishlist->id,
        ]);
    }
}
