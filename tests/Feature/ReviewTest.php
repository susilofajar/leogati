<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected User $verifiedBuyer;
    protected User $unverifiedUser;
    protected User $admin;
    protected Product $product;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['display_name' => 'Pelanggan']);
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);

        $this->verifiedBuyer = User::factory()->create(['name' => 'Pembeli Asli']);
        $this->verifiedBuyer->roles()->attach($customerRole);

        $this->unverifiedUser = User::factory()->create(['name' => 'Pengguna Belum Beli']);
        $this->unverifiedUser->roles()->attach($customerRole);

        $this->admin = User::factory()->create(['name' => 'Admin Toko']);
        $this->admin->roles()->attach($adminRole);

        Warehouse::create(['name' => 'Gudang Pusat', 'code' => 'GDG-PST', 'is_default' => true]);
        $category = Category::create(['name' => 'Monitor', 'slug' => 'monitor']);
        $brand = Brand::create(['name' => 'LG', 'slug' => 'lg']);

        $this->product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'LG UltraGear 27 Inch 144Hz',
            'slug' => 'lg-ultragear-27-inch',
            'status' => 'active',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $this->product->id,
            'name' => '27GN650 IPS FHD',
            'sku' => 'LG-27GN650',
            'price' => 3500000,
            'stock' => 10,
        ]);

        // Buat pesanan yang sudah berstatus 'delivered' untuk $this->verifiedBuyer
        $this->order = Order::create([
            'user_id' => $this->verifiedBuyer->id,
            'order_number' => 'LEO-20260819-TEST',
            'subtotal_amount' => 3500000,
            'shipping_amount' => 15000,
            'discount_amount' => 0,
            'total_amount' => 3515000,
            'status' => 'delivered',
            'payment_method' => 'bca_va',
            'payment_status' => 'paid',
            'shipping_courier' => 'jne',
            'shipping_service' => 'Reguler',
            'shipping_address' => [
                'recipient_name' => 'Pembeli Asli',
                'phone_number' => '081234567890',
                'address_line' => 'Jl. Monitor No. 1',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12345',
            ],
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_variant_id' => $variant->id,
            'product_name' => $this->product->name,
            'variant_name' => $variant->name,
            'sku' => $variant->sku,
            'unit_price' => 3500000,
            'quantity' => 1,
            'subtotal' => 3500000,
            'weight_grams' => 4000,
        ]);
    }

    public function test_verified_buyer_can_submit_review(): void
    {
        $response = $this->actingAs($this->verifiedBuyer)
            ->post(route('products.reviews.store', $this->product->slug), [
                'rating' => 5,
                'title' => 'Monitor gaming terbaik di kelasnya!',
                'comment' => 'Warna panel IPS sangat akurat, refresh rate 144Hz mulus tanpa ghosting sama sekali.',
            ]);

        $response->assertRedirect(route('products.show', $this->product->slug));

        $this->assertDatabaseHas('reviews', [
            'product_id' => $this->product->id,
            'user_id' => $this->verifiedBuyer->id,
            'rating' => 5,
            'is_verified_purchase' => true,
        ]);

        // Cek rata-rata rating produk terupdate
        $this->assertEquals(5.0, $this->product->fresh()->average_rating);
        $this->assertEquals(1, $this->product->fresh()->reviews_count);
    }

    public function test_unverified_user_cannot_submit_review(): void
    {
        $response = $this->actingAs($this->unverifiedUser)
            ->post(route('products.reviews.store', $this->product->slug), [
                'rating' => 5,
                'title' => 'Mencoba mengulas tanpa membeli',
                'comment' => 'Ulasan dari akun yang belum pernah membeli barang ini.',
            ]);

        $response->assertSessionHasErrors('rating');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_buyer_cannot_submit_duplicate_review_for_same_order(): void
    {
        // Ulasan pertama
        $this->actingAs($this->verifiedBuyer)
            ->post(route('products.reviews.store', $this->product->slug), [
                'rating' => 5,
                'comment' => 'Ulasan pertama yang sah dan terverifikasi.',
            ]);

        // Ulasan kedua untuk pesanan yang sama
        $response = $this->actingAs($this->verifiedBuyer)
            ->post(route('products.reviews.store', $this->product->slug), [
                'rating' => 4,
                'comment' => 'Mencoba mengirim ulasan duplikat kedua kalinya.',
            ]);

        $response->assertSessionHasErrors('rating');
        $this->assertDatabaseCount('reviews', 1);
    }

    public function test_admin_can_moderate_and_reply_to_review(): void
    {
        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => $this->verifiedBuyer->id,
            'order_id' => $this->order->id,
            'rating' => 5,
            'title' => 'Mantap',
            'comment' => 'Pengiriman cepat sampai di hari yang sama.',
            'is_verified_purchase' => true,
            'is_approved' => true,
        ]);

        // Admin lihat daftar dan detail ulasan
        $this->actingAs($this->admin)->get(route('admin.ulasan.index'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.ulasan.show', $review->id))->assertStatus(200);

        // Admin sembunyikan ulasan
        $this->actingAs($this->admin)->post(route('admin.ulasan.toggle', $review->id));
        $this->assertFalse($review->fresh()->is_approved);

        // Admin balas ulasan
        $this->actingAs($this->admin)->post(route('admin.ulasan.reply', $review->id), [
            'admin_reply' => 'Terima kasih atas kepercayaannya berbelanja di LEOGATISTORE!',
        ]);

        $this->assertNotNull($review->fresh()->admin_reply);
        $this->assertNotNull($review->fresh()->admin_replied_at);
    }
}
