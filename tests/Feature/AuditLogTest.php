<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected ProductVariant $variant;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['display_name' => 'Pelanggan']);

        $this->admin = User::factory()->create(['name' => 'Admin Security']);
        $this->admin->roles()->attach($adminRole);

        $this->customer = User::factory()->create(['name' => 'Budi Customer']);
        $this->customer->roles()->attach($customerRole);

        Warehouse::create(['name' => 'Gudang Audit', 'code' => 'GDG-AUD', 'is_default' => true]);
        $cat = Category::create(['name' => 'Keyboard', 'slug' => 'keyboard']);
        $brand = Brand::create(['name' => 'Logitech', 'slug' => 'logitech']);

        $prod = Product::create([
            'category_id' => $cat->id,
            'brand_id' => $brand->id,
            'name' => 'Logitech G915 TKL',
            'slug' => 'logitech-g915-tkl',
            'status' => 'active',
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $prod->id,
            'name' => 'GL Tactile Wireless',
            'sku' => 'LOGI-G915-TAC',
            'price' => 3100000,
            'stock' => 10,
        ]);

        $this->order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'LEO-AUD-001',
            'subtotal_amount' => 3100000,
            'shipping_amount' => 15000,
            'discount_amount' => 0,
            'total_amount' => 3115000,
            'status' => 'paid',
            'payment_method' => 'bca_va',
            'payment_status' => 'paid',
            'shipping_courier' => 'jne',
            'shipping_service' => 'Reguler',
            'shipping_address' => ['recipient_name' => 'Budi', 'phone_number' => '081', 'address_line' => 'Alamat', 'city' => 'Jakarta', 'province' => 'DKI', 'postal_code' => '111'],
        ]);
    }

    public function test_updating_order_status_creates_audit_log(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.pesanan.update_status', $this->order->id), [
            'status' => 'shipped',
            'payment_status' => 'paid',
            'shipping_tracking_number' => 'JNE123456789',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'order_status_updated',
            'target_type' => 'Order',
            'target_id' => $this->order->id,
            'user_name' => 'Admin Security',
        ]);
    }

    public function test_manual_stock_adjustment_creates_audit_log(): void
    {
        $warehouse = Warehouse::first();

        $response = $this->actingAs($this->admin)->post(route('admin.inventaris.adjust', $this->variant->id), [
            'warehouse_id' => $warehouse->id,
            'quantity_change' => -2,
            'notes' => 'Unit rusak saat pemindahan rak',
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'stock_adjusted',
            'target_type' => 'ProductVariant',
            'target_id' => $this->variant->id,
        ]);
    }

    public function test_admin_can_view_audit_logs_page(): void
    {
        AuditLog::create([
            'user_id' => $this->admin->id,
            'user_name' => 'Admin Security',
            'action' => 'test_action',
            'target_type' => 'Test',
            'target_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => ['test' => 'data'],
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.audit_log.index'));
        $response->assertStatus(200);
        $response->assertSee('Jejak Audit Keamanan');
        $response->assertSee('test_action');
    }

    public function test_customer_cannot_access_audit_logs_page(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.audit_log.index'));
        $response->assertStatus(403);
    }
}
