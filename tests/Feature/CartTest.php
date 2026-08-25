<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RoleAndPermissionSeeder::class,
            CatalogBaseSeeder::class,
            ProductCatalogSeeder::class,
        ]);
        $this->user = User::where('email', 'pelanggan@example.com')->first();
    }

    /**
     * Test shopping cart page can be rendered.
     */
    public function test_cart_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->user)->get('/keranjang');

        $response->assertStatus(200);
        $response->assertSee('Keranjang Belanja');
    }

    /**
     * Test adding a product variant to the cart.
     */
    public function test_can_add_product_to_cart(): void
    {
        $variant = ProductVariant::first();

        $response = $this->actingAs($this->user)->post('/keranjang/tambah', [
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('cart_items', [
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);
    }

    /**
     * Test cannot add more than available stock to cart.
     */
    public function test_cannot_add_exceeding_stock_to_cart(): void
    {
        $variant = ProductVariant::first();
        $excessiveQty = $variant->stock + 100;

        $response = $this->actingAs($this->user)->post('/keranjang/tambah', [
            'product_variant_id' => $variant->id,
            'quantity' => $excessiveQty,
        ]);

        $response->assertSessionHasErrors('quantity');
    }

    /**
     * Test updating quantity in cart.
     */
    public function test_can_update_cart_item_quantity(): void
    {
        $variant = ProductVariant::first();

        $this->actingAs($this->user)->post('/keranjang/tambah', [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $cartItem = CartItem::where('product_variant_id', $variant->id)->first();

        $response = $this->actingAs($this->user)->put('/keranjang/ubah/' . $cartItem->id, [
            'quantity' => 3,
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3,
        ]);
    }

    /**
     * Test removing item from cart.
     */
    public function test_can_remove_item_from_cart(): void
    {
        $variant = ProductVariant::first();

        $this->actingAs($this->user)->post('/keranjang/tambah', [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $cartItem = CartItem::where('product_variant_id', $variant->id)->first();

        $response = $this->actingAs($this->user)->delete('/keranjang/hapus/' . $cartItem->id);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }

    /**
     * Test clearing the whole cart.
     */
    public function test_can_clear_cart(): void
    {
        $variant = ProductVariant::first();

        $this->actingAs($this->user)->post('/keranjang/tambah', [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->user)->delete('/keranjang/kosongkan');

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseCount('cart_items', 0);
    }
}
