<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\SerialNumber;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Product $product;
    protected ProductVariant $variant;
    protected SerialNumber $serialNumber;

    protected function setUp(): void
    {
        parent::setUp();

        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['display_name' => 'Pelanggan']);

        $this->user = User::factory()->create([
            'name' => 'Budi API',
            'email' => 'budi.api@example.com',
            'password' => bcrypt('Password123!'),
        ]);
        $this->user->roles()->attach($customerRole);

        Warehouse::create(['name' => 'Gudang API', 'code' => 'GDG-API', 'is_default' => true]);
        $category = Category::create(['name' => 'Prosesor', 'slug' => 'prosesor', 'is_active' => true]);
        $brand = Brand::create(['name' => 'Intel', 'slug' => 'intel', 'is_active' => true]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Intel Core i7-14700K',
            'slug' => 'intel-core-i7-14700k',
            'status' => 'active',
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'name' => 'Box Edition, LGA1700',
            'sku' => 'INTEL-I7-14700K',
            'price' => 6800000,
            'stock' => 15,
        ]);

        $specGroup = \App\Models\SpecificationGroup::create([
            'name' => 'Processor',
            'slug' => 'processor',
        ]);

        $attrSocket = \App\Models\SpecificationAttribute::create([
            'group_id' => $specGroup->id,
            'name' => 'Socket',
            'slug' => 'cpu_socket',
        ]);

        $attrTdp = \App\Models\SpecificationAttribute::create([
            'group_id' => $specGroup->id,
            'name' => 'TDP',
            'slug' => 'cpu_tdp',
            'unit' => 'W',
        ]);

        ProductSpecification::create([
            'product_id' => $this->product->id,
            'attribute_id' => $attrSocket->id,
            'value' => 'LGA1700',
        ]);

        ProductSpecification::create([
            'product_id' => $this->product->id,
            'attribute_id' => $attrTdp->id,
            'value' => '125',
        ]);

        $this->serialNumber = SerialNumber::create([
            'serial_number' => 'LEO-SN-API-9999',
            'product_variant_id' => $this->variant->id,
            'status' => 'sold',
            'customer_id' => $this->user->id,
            'purchased_at' => Carbon::now()->subMonths(2),
            'warranty_expires_at' => Carbon::now()->addMonths(22),
        ]);
    }

    public function test_can_register_via_api(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Pengguna Baru API',
            'email' => 'baru@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                    'token_type',
                ],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'baru@example.com']);
    }

    public function test_can_login_via_api_and_get_sanctum_token(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'budi.api@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'token_type',
                ],
            ]);
    }

    public function test_can_access_protected_user_endpoint_with_bearer_token(): void
    {
        $token = $this->user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/user');

        $response->assertStatus(200)
            ->assertJsonPath('data.email', 'budi.api@example.com');
    }

    public function test_unauthorized_user_cannot_access_protected_endpoint(): void
    {
        $response = $this->getJson('/api/v1/auth/user');
        $response->assertStatus(401);
    }

    public function test_can_fetch_public_product_catalog_and_details(): void
    {
        // Index
        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => ['data', 'links', 'meta'],
            ]);

        // Show
        $showResponse = $this->getJson('/api/v1/products/' . $this->product->slug);
        $showResponse->assertStatus(200)
            ->assertJsonPath('data.name', 'Intel Core i7-14700K')
            ->assertJsonPath('data.category.name', 'Prosesor')
            ->assertJsonPath('data.brand.name', 'Intel');
    }

    public function test_can_fetch_categories_and_brands(): void
    {
        $this->getJson('/api/v1/categories')->assertStatus(200);
        $this->getJson('/api/v1/brands')->assertStatus(200);
    }

    public function test_can_check_warranty_via_api(): void
    {
        $response = $this->getJson('/api/v1/warranty/check?serial_number=LEO-SN-API-9999');

        $response->assertStatus(200)
            ->assertJsonPath('data.serial_number', 'LEO-SN-API-9999')
            ->assertJsonPath('data.is_warranty_active', true);
    }

    public function test_can_validate_pc_builder_via_api(): void
    {
        $response = $this->postJson('/api/v1/pc-builder/validate', [
            'components' => [
                'cpu' => $this->variant->id,
            ],
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.compatibility.is_compatible', true);

        $this->assertGreaterThan(100, $response->json('data.power.estimated_wattage'));
    }

    public function test_user_can_fetch_own_orders_via_api(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_number' => 'LEO-API-ORD-001',
            'subtotal_amount' => 6800000,
            'shipping_amount' => 20000,
            'discount_amount' => 0,
            'total_amount' => 6820000,
            'status' => 'delivered',
            'payment_method' => 'bca_va',
            'payment_status' => 'paid',
            'shipping_courier' => 'jne',
            'shipping_service' => 'Reguler',
            'shipping_address' => ['recipient_name' => 'Budi API', 'phone_number' => '081', 'address_line' => 'Alamat', 'city' => 'Jakarta', 'province' => 'DKI', 'postal_code' => '111'],
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_variant_id' => $this->variant->id,
            'product_name' => $this->product->name,
            'variant_name' => $this->variant->name,
            'sku' => $this->variant->sku,
            'unit_price' => 6800000,
            'quantity' => 1,
            'subtotal' => 6800000,
            'weight_grams' => 500,
        ]);

        $token = $this->user->createToken('test_token')->plainTextToken;

        // List Orders
        $listResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/orders');

        $listResponse->assertStatus(200)
            ->assertJsonPath('data.data.0.order_number', 'LEO-API-ORD-001');

        // Show Order
        $showResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/orders/LEO-API-ORD-001');

        $showResponse->assertStatus(200)
            ->assertJsonPath('data.order_number', 'LEO-API-ORD-001')
            ->assertJsonPath('data.items.0.product_name', 'Intel Core i7-14700K');
    }
}
