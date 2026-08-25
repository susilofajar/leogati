<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\OrderService;
use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
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
     * Create a dummy order for a given user.
     */
    protected function createDummyOrder(User $user): Order
    {
        $variant = ProductVariant::first();
        $cart = \App\Models\Cart::create(['user_id' => $user->id]);
        \App\Models\CartItem::create([
            'cart_id' => $cart->id,
            'product_variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $orderService = app(OrderService::class);
        return $orderService->createOrder($user, $cart, [
            'recipient_name' => $user->name,
            'phone_number' => '081234567890',
            'address_line' => 'Jl. Kebon Jeruk No. 12',
            'city' => 'Jakarta Barat',
            'province' => 'DKI Jakarta',
            'postal_code' => '11530',
            'shipping_courier' => 'jne',
            'payment_method' => 'bca_va',
        ]);
    }

    /**
     * Test customer can view order list and detail.
     */
    public function test_customer_can_view_own_orders_and_detail(): void
    {
        $user = User::where('email', 'pelanggan@example.com')->first();
        $order = $this->createDummyOrder($user);

        // List orders
        $responseList = $this->actingAs($user)->get('/akun/pesanan');
        $responseList->assertStatus(200);
        $responseList->assertSee($order->order_number);

        // Show order detail
        $responseDetail = $this->actingAs($user)->get('/akun/pesanan/' . $order->order_number);
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee($order->order_number);
        $responseDetail->assertSee('Instruksi Pembayaran');
    }

    /**
     * Test customer cannot view another customer's order.
     */
    public function test_customer_cannot_view_other_customers_order(): void
    {
        $user1 = User::where('email', 'pelanggan@example.com')->first();
        $user2 = User::factory()->create();

        $order1 = $this->createDummyOrder($user1);

        $response = $this->actingAs($user2)->get('/akun/pesanan/' . $order1->order_number);
        $response->assertStatus(403);
    }

    /**
     * Test admin can view all orders and update order status.
     */
    public function test_admin_can_view_and_update_orders(): void
    {
        $admin = User::where('email', 'admin@leogati.store')->first();
        $customer = User::where('email', 'pelanggan@example.com')->first();
        $order = $this->createDummyOrder($customer);

        // Admin view orders list
        $responseList = $this->actingAs($admin)->get('/admin/pesanan');
        $responseList->assertStatus(200);
        $responseList->assertSee($order->order_number);

        // Admin view order detail
        $responseDetail = $this->actingAs($admin)->get('/admin/pesanan/' . $order->id);
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee($order->order_number);

        // Admin update status to shipped with tracking number
        $responseUpdate = $this->actingAs($admin)->put('/admin/pesanan/' . $order->id . '/status', [
            'status' => 'shipped',
            'payment_status' => 'paid',
            'shipping_tracking_number' => 'JNE123456789ID',
        ]);

        $responseUpdate->assertRedirect(route('admin.pesanan.show', $order->id));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped',
            'payment_status' => 'paid',
            'shipping_tracking_number' => 'JNE123456789ID',
        ]);
    }
}
