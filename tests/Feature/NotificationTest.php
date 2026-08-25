<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $otherCustomer;
    protected User $admin;
    protected ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['display_name' => 'Pelanggan']);
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);

        $this->customer = User::factory()->create(['name' => 'Rian Notif']);
        $this->customer->roles()->attach($customerRole);

        $this->otherCustomer = User::factory()->create(['name' => 'Doni Pelanggan']);
        $this->otherCustomer->roles()->attach($customerRole);

        $this->admin = User::factory()->create(['name' => 'Admin Utama']);
        $this->admin->roles()->attach($adminRole);

        Warehouse::create(['name' => 'Gudang Notif', 'code' => 'GDG-NTF', 'is_default' => true]);
        $cat = Category::create(['name' => 'Headset', 'slug' => 'headset']);
        $brand = Brand::create(['name' => 'Razer', 'slug' => 'razer']);

        $prod = Product::create([
            'category_id' => $cat->id,
            'brand_id' => $brand->id,
            'name' => 'Razer BlackShark V2 Pro',
            'slug' => 'razer-blackshark-v2-pro',
            'status' => 'active',
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $prod->id,
            'name' => 'Wireless Black Edition',
            'sku' => 'RZ-BS-V2P',
            'price' => 2500000,
            'stock' => 10,
        ]);
    }

    public function test_customer_receives_in_app_notification_when_ordering(): void
    {
        // Masukkan ke keranjang dan checkout
        $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->post(route('checkout.process'), [
            'recipient_name' => 'Rian Notif',
            'phone_number' => '081234567890',
            'address_line' => 'Jl. Merdeka No. 1',
            'city' => 'Jakarta Barat',
            'province' => 'DKI Jakarta',
            'postal_code' => '11530',
            'shipping_courier' => 'jne',
            'payment_method' => 'bca_va',
        ]);

        $response->assertStatus(302);

        // Pastikan ada notifikasi di database milik customer
        $this->assertEquals(1, $this->customer->notifications()->count());
        $notification = $this->customer->notifications()->first();
        $this->assertEquals('order_created', $notification->data['type']);
        $this->assertNull($notification->read_at);
    }

    public function test_customer_can_view_notifications_page(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'LEO-NTF-001',
            'subtotal_amount' => 2500000,
            'shipping_amount' => 15000,
            'discount_amount' => 0,
            'total_amount' => 2515000,
            'status' => 'awaiting_payment',
            'payment_method' => 'bca_va',
            'payment_status' => 'unpaid',
            'shipping_courier' => 'jne',
            'shipping_service' => 'Reguler',
            'shipping_address' => ['recipient_name' => 'Rian', 'phone_number' => '081', 'address_line' => 'Alamat', 'city' => 'Jakarta', 'province' => 'DKI', 'postal_code' => '111'],
        ]);

        $this->customer->notify(new OrderCreatedNotification($order));

        $response = $this->actingAs($this->customer)->get(route('customer.notifications.index'));
        $response->assertStatus(200);
        $response->assertSee('Pusat Notifikasi');
        $response->assertSee('LEO-NTF-001');
    }

    public function test_customer_can_mark_notification_as_read(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'LEO-NTF-002',
            'subtotal_amount' => 2500000,
            'shipping_amount' => 15000,
            'discount_amount' => 0,
            'total_amount' => 2515000,
            'status' => 'paid',
            'payment_method' => 'bca_va',
            'payment_status' => 'paid',
            'shipping_courier' => 'jne',
            'shipping_service' => 'Reguler',
            'shipping_address' => ['recipient_name' => 'Rian', 'phone_number' => '081', 'address_line' => 'Alamat', 'city' => 'Jakarta', 'province' => 'DKI', 'postal_code' => '111'],
        ]);

        $this->customer->notify(new OrderStatusUpdatedNotification($order, 'awaiting_payment'));
        $notif = $this->customer->unreadNotifications()->first();

        // Tandai dibaca
        $response = $this->actingAs($this->customer)->post(route('customer.notifications.read', $notif->id));
        $response->assertStatus(302);

        $this->assertNotNull($notif->fresh()->read_at);
        $this->assertEquals(0, $this->customer->unreadNotifications()->count());
    }

    public function test_customer_cannot_mark_other_customers_notification(): void
    {
        $order = Order::create([
            'user_id' => $this->otherCustomer->id,
            'order_number' => 'LEO-NTF-003',
            'subtotal_amount' => 2500000,
            'shipping_amount' => 15000,
            'discount_amount' => 0,
            'total_amount' => 2515000,
            'status' => 'paid',
            'payment_method' => 'bca_va',
            'payment_status' => 'paid',
            'shipping_courier' => 'jne',
            'shipping_service' => 'Reguler',
            'shipping_address' => ['recipient_name' => 'Doni', 'phone_number' => '081', 'address_line' => 'Alamat', 'city' => 'Jakarta', 'province' => 'DKI', 'postal_code' => '111'],
        ]);

        $this->otherCustomer->notify(new OrderCreatedNotification($order));
        $otherNotif = $this->otherCustomer->notifications()->first();

        // Customer lain mencoba mengakses notifikasi Doni
        $response = $this->actingAs($this->customer)->post(route('customer.notifications.read', $otherNotif->id));
        $response->assertStatus(404);
    }
}
