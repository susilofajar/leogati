<?php

namespace Tests\Feature;

use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
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
     * Test guest cannot access checkout page.
     */
    public function test_guest_is_redirected_to_login_from_checkout(): void
    {
        $response = $this->get('/checkout');

        $response->assertRedirect(route('login'));
    }

    /**
     * Test authenticated user with empty cart is redirected to cart page.
     */
    public function test_user_with_empty_cart_is_redirected_from_checkout(): void
    {
        $user = User::where('email', 'pelanggan@example.com')->first();

        $response = $this->actingAs($user)->get('/checkout');

        $response->assertRedirect(route('cart.index'));
    }

    /**
     * Test successful checkout with atomic order creation and stock deduction.
     */
    public function test_authenticated_user_can_checkout_successfully(): void
    {
        $user = User::where('email', 'pelanggan@example.com')->first();
        $variant = ProductVariant::first();
        $initialStock = $variant->stock;

        // Add item to cart
        $cartService = app(CartService::class);
        $this->actingAs($user);
        $cartService->addItem($variant->id, 2);

        $checkoutData = [
            'recipient_name' => 'Budi Santoso',
            'phone_number' => '081234567890',
            'address_line' => 'Jl. Jenderal Sudirman No. 45',
            'city' => 'Jakarta Selatan',
            'province' => 'DKI Jakarta',
            'postal_code' => '12190',
            'shipping_courier' => 'jne',
            'payment_method' => 'bca_va',
            'notes' => 'Tolong bubble wrap tebal',
        ];

        $response = $this->post('/checkout', $checkoutData);

        // Verify order created and redirected to order show
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'bca_va',
            'status' => 'awaiting_payment',
        ]);

        $order = \App\Models\Order::where('user_id', $user->id)->first();
        $response->assertRedirect(route('customer.orders.show', $order->order_number));

        // Verify Order Items created
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        // Verify Stock Deducted atomically
        $variant->refresh();
        $this->assertEquals($initialStock - 2, $variant->stock);

        // Verify Payment Record created
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'payment_method' => 'bca_va',
            'status' => 'pending',
        ]);

        // Verify Cart is cleared
        $cart = $cartService->getCart($user);
        $this->assertCount(0, $cart->items);
    }
}
