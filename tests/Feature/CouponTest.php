<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $admin;
    protected ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['display_name' => 'Pelanggan']);
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);

        $this->customer = User::factory()->create(['name' => 'Budi Pembeli']);
        $this->customer->roles()->attach($customerRole);

        $this->admin = User::factory()->create(['name' => 'Admin Promo']);
        $this->admin->roles()->attach($adminRole);

        Warehouse::create(['name' => 'Gudang Utama', 'code' => 'GDG-01', 'is_default' => true]);
        $category = Category::create(['name' => 'Laptop', 'slug' => 'laptop']);
        $brand = Brand::create(['name' => 'ASUS', 'slug' => 'asus']);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'ASUS Zenbook 14 OLED',
            'slug' => 'asus-zenbook-14-oled',
            'status' => 'active',
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Intel Core Ultra 7 / 16GB / 1TB',
            'sku' => 'ZEN-14-U7',
            'price' => 20000000,
            'stock' => 10,
        ]);
    }

    public function test_can_validate_and_apply_percentage_coupon_with_cap(): void
    {
        $coupon = Coupon::create([
            'code' => 'DISKON10PERSEN',
            'name' => 'Diskon 10% Maks 500rb',
            'type' => 'percent',
            'value' => 10,
            'max_discount_amount' => 500000,
            'is_active' => true,
        ]);

        // 10% dari 20jt adalah 2jt, namun dibatasi max_discount_amount 500rb
        $discount = $coupon->calculateDiscount(20000000);
        $this->assertEquals(500000, $discount);
    }

    public function test_can_validate_and_apply_fixed_amount_coupon(): void
    {
        $coupon = Coupon::create([
            'code' => 'POTONGAN100K',
            'name' => 'Potongan 100 Ribu',
            'type' => 'fixed',
            'value' => 100000,
            'min_purchase_amount' => 500000,
            'is_active' => true,
        ]);

        $this->assertEquals(100000, $coupon->calculateDiscount(1000000));
        // Jika di bawah min_purchase_amount maka diskon 0
        $this->assertEquals(0, $coupon->calculateDiscount(400000));
    }

    public function test_customer_can_apply_coupon_to_cart_and_checkout(): void
    {
        $coupon = Coupon::create([
            'code' => 'HEMAT50K',
            'name' => 'Hemat 50 Ribu',
            'type' => 'fixed',
            'value' => 50000,
            'is_active' => true,
        ]);

        // Tambah ke keranjang
        $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
        ]);

        // Terapkan kupon
        $response = $this->actingAs($this->customer)->post(route('cart.apply_coupon'), [
            'coupon_code' => 'HEMAT50K',
        ]);
        $response->assertSessionHas('applied_coupon', 'HEMAT50K');

        // Checkout dengan kupon
        $checkoutResponse = $this->actingAs($this->customer)->post(route('checkout.process'), [
            'recipient_name' => 'Budi Pembeli',
            'phone_number' => '081234567890',
            'address_line' => 'Jl. Sudirman No. 10',
            'city' => 'Jakarta Pusat',
            'province' => 'DKI Jakarta',
            'postal_code' => '10110',
            'shipping_courier' => 'jne',
            'payment_method' => 'bca_va',
        ]);

        $checkoutResponse->assertStatus(302);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'discount_amount' => 50000,
            'coupon_code' => 'HEMAT50K',
        ]);

        // Kuota pemakaian kupon bertambah
        $this->assertEquals(1, $coupon->fresh()->used_count);
    }

    public function test_cannot_use_expired_or_inactive_coupon(): void
    {
        $expiredCoupon = Coupon::create([
            'code' => 'KEDALUWARSA',
            'name' => 'Kupon Lampau',
            'type' => 'fixed',
            'value' => 20000,
            'end_date' => Carbon::now()->subDays(2),
            'is_active' => true,
        ]);

        $this->actingAs($this->customer)->post(route('cart.add'), [
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->post(route('cart.apply_coupon'), [
            'coupon_code' => 'KEDALUWARSA',
        ]);

        $response->assertSessionHasErrors('coupon_code');
    }

    public function test_admin_can_manage_coupons(): void
    {
        // Admin buat kupon baru
        $response = $this->actingAs($this->admin)->post(route('admin.kupon.store'), [
            'code' => 'PROMOADMIN20',
            'name' => 'Promo Diskon Admin 20%',
            'type' => 'percent',
            'value' => 20,
            'min_purchase_amount' => 100000,
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('coupons', ['code' => 'PROMOADMIN20', 'value' => 20]);

        $coupon = Coupon::where('code', 'PROMOADMIN20')->first();

        // Admin edit kupon
        $this->actingAs($this->admin)->put(route('admin.kupon.update', $coupon->id), [
            'code' => 'PROMOADMIN25',
            'name' => 'Promo Diskon Admin 25%',
            'type' => 'percent',
            'value' => 25,
            'min_purchase_amount' => 100000,
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('coupons', ['code' => 'PROMOADMIN25', 'value' => 25]);

        // Admin hapus kupon
        $this->actingAs($this->admin)->delete(route('admin.kupon.destroy', $coupon->id));
        $this->assertDatabaseMissing('coupons', ['code' => 'PROMOADMIN25']);
    }
}
