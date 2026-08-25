<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\SerialNumber;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarrantyClaim;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarrantyClaimTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $otherCustomer;
    protected User $admin;
    protected SerialNumber $activeSerial;
    protected SerialNumber $expiredSerial;

    protected function setUp(): void
    {
        parent::setUp();

        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['display_name' => 'Pelanggan']);
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);

        $this->customer = User::factory()->create(['name' => 'Budi Pelanggan']);
        $this->customer->roles()->attach($customerRole);

        $this->otherCustomer = User::factory()->create(['name' => 'Andi Pelanggan']);
        $this->otherCustomer->roles()->attach($customerRole);

        $this->admin = User::factory()->create(['name' => 'Admin Utama']);
        $this->admin->roles()->attach($adminRole);

        $warehouse = Warehouse::create([
            'name' => 'Gudang Pusat',
            'code' => 'GDG-PST',
            'is_default' => true,
        ]);

        $category = Category::create(['name' => 'Laptop', 'slug' => 'laptop']);
        $brand = Brand::create(['name' => 'ASUS', 'slug' => 'asus']);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'ASUS ROG Zephyrus G16',
            'slug' => 'asus-rog-zephyrus-g16',
            'status' => 'active',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'RTX 4070 / 32GB RAM / 1TB SSD',
            'sku' => 'ROG-G16-4070',
            'price' => 30000000,
            'stock' => 10,
            'is_serialized' => true,
        ]);

        // Unit aktif milik $this->customer (garansi 1 tahun ke depan)
        $this->activeSerial = SerialNumber::create([
            'serial_number' => 'ROG-ACTIVE-001',
            'product_variant_id' => $variant->id,
            'warehouse_id' => $warehouse->id,
            'customer_id' => $this->customer->id,
            'status' => 'sold',
            'purchased_at' => Carbon::now()->subMonths(2),
            'sold_at' => Carbon::now()->subMonths(1),
            'warranty_expires_at' => Carbon::now()->addMonths(11),
        ]);

        // Unit kadaluarsa milik $this->customer (garansi habis 1 bulan lalu)
        $this->expiredSerial = SerialNumber::create([
            'serial_number' => 'ROG-EXPIRED-999',
            'product_variant_id' => $variant->id,
            'warehouse_id' => $warehouse->id,
            'customer_id' => $this->customer->id,
            'status' => 'sold',
            'purchased_at' => Carbon::now()->subMonths(14),
            'sold_at' => Carbon::now()->subMonths(13),
            'warranty_expires_at' => Carbon::now()->subMonth(),
        ]);
    }

    public function test_customer_can_render_claim_form(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('warranty.claim_form', ['sn' => $this->activeSerial->serial_number]));

        $response->assertStatus(200);
        $response->assertSee('Pengajuan Klaim Garansi Resmi');
        $response->assertSee($this->activeSerial->serial_number);
    }

    public function test_customer_can_submit_valid_warranty_claim(): void
    {
        $response = $this->actingAs($this->customer)->post(route('warranty.submit_claim'), [
            'serial_number' => $this->activeSerial->serial_number,
            'issue_category' => 'malfunction',
            'issue_description' => 'Layar laptop berkedip (flickering) saat membuka aplikasi grafis berat dan kadang layar menjadi hitam total.',
        ]);

        $this->assertDatabaseHas('warranty_claims', [
            'serial_number_id' => $this->activeSerial->id,
            'customer_id' => $this->customer->id,
            'issue_category' => 'malfunction',
            'status' => 'submitted',
        ]);

        // Status serial number harus berganti menjadi 'warranty'
        $this->assertEquals('warranty', $this->activeSerial->fresh()->status);

        $claim = WarrantyClaim::first();
        $response->assertRedirect(route('customer.warranty.show', $claim->claim_number));
    }

    public function test_customer_cannot_submit_claim_for_expired_warranty(): void
    {
        $response = $this->actingAs($this->customer)->post(route('warranty.submit_claim'), [
            'serial_number' => $this->expiredSerial->serial_number,
            'issue_category' => 'malfunction',
            'issue_description' => 'Keyboard beberapa tombol tidak merespons setelah pemakaian normal harian.',
        ]);

        $response->assertSessionHasErrors('serial_number');
        $this->assertDatabaseCount('warranty_claims', 0);
    }

    public function test_customer_cannot_submit_claim_for_another_customers_serial(): void
    {
        // Other customer mencoba klaim unit milik customer
        $response = $this->actingAs($this->otherCustomer)->post(route('warranty.submit_claim'), [
            'serial_number' => $this->activeSerial->serial_number,
            'issue_category' => 'malfunction',
            'issue_description' => 'Mencoba mengklaim unit orang lain tanpa otorisasi sah.',
        ]);

        $response->assertSessionHasErrors('serial_number');
        $this->assertDatabaseCount('warranty_claims', 0);
    }

    public function test_customer_cannot_submit_duplicate_active_claim(): void
    {
        // Klaim pertama
        $this->actingAs($this->customer)->post(route('warranty.submit_claim'), [
            'serial_number' => $this->activeSerial->serial_number,
            'issue_category' => 'malfunction',
            'issue_description' => 'Klaim pertama yang sah dan valid untuk unit ini.',
        ]);

        // Klaim kedua untuk unit yang sama saat status masih submitted
        $response = $this->actingAs($this->customer)->post(route('warranty.submit_claim'), [
            'serial_number' => $this->activeSerial->serial_number,
            'issue_category' => 'defective',
            'issue_description' => 'Mencoba mengajukan klaim kedua saat klaim pertama masih berjalan.',
        ]);

        $response->assertSessionHasErrors('serial_number');
        $this->assertDatabaseCount('warranty_claims', 1);
    }

    public function test_customer_can_view_own_claims_list_and_detail(): void
    {
        $claim = WarrantyClaim::create([
            'claim_number' => 'WC-20260819-0001',
            'serial_number_id' => $this->activeSerial->id,
            'customer_id' => $this->customer->id,
            'issue_category' => 'malfunction',
            'issue_description' => 'Masalah pada port audio output mengalami distorsi suara.',
            'status' => 'submitted',
            'submitted_at' => Carbon::now(),
        ]);

        $responseList = $this->actingAs($this->customer)->get(route('customer.warranty.index'));
        $responseList->assertStatus(200);
        $responseList->assertSee('WC-20260819-0001');

        $responseDetail = $this->actingAs($this->customer)->get(route('customer.warranty.show', $claim->claim_number));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('WC-20260819-0001');
        $responseDetail->assertSee('Diajukan');
    }

    public function test_customer_cannot_view_other_customers_claim_detail(): void
    {
        $claim = WarrantyClaim::create([
            'claim_number' => 'WC-20260819-0002',
            'serial_number_id' => $this->activeSerial->id,
            'customer_id' => $this->customer->id,
            'issue_category' => 'malfunction',
            'issue_description' => 'Klaim milik customer 1.',
            'status' => 'submitted',
            'submitted_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($this->otherCustomer)->get(route('customer.warranty.show', $claim->claim_number));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_and_process_warranty_claim(): void
    {
        $claim = WarrantyClaim::create([
            'claim_number' => 'WC-20260819-0003',
            'serial_number_id' => $this->activeSerial->id,
            'customer_id' => $this->customer->id,
            'issue_category' => 'defective',
            'issue_description' => 'Cacat pabrik pada panel LCD terdapat dead pixel di tengah layar.',
            'status' => 'submitted',
            'submitted_at' => Carbon::now(),
        ]);
        $this->activeSerial->update(['status' => 'warranty']);

        // Admin lihat daftar & detail
        $this->actingAs($this->admin)->get(route('admin.garansi.index'))->assertStatus(200)->assertSee('WC-20260819-0003');
        $this->actingAs($this->admin)->get(route('admin.garansi.show', $claim->id))->assertStatus(200);

        // Admin ubah status ke in_repair
        $this->actingAs($this->admin)->put(route('admin.garansi.update_status', $claim->id), [
            'status' => 'in_repair',
            'admin_notes' => 'Unit telah diterima di service center pusat Jakarta, penggantian panel LCD sedang diproses.',
        ]);

        $this->assertEquals('in_repair', $claim->fresh()->status);

        // Admin selesaikan klaim ke repaired (resolusi wajib diisi)
        $this->actingAs($this->admin)->put(route('admin.garansi.update_status', $claim->id), [
            'status' => 'repaired',
            'admin_notes' => 'Panel LCD telah diganti dengan modul baru original ASUS.',
            'resolution' => 'Penggantian panel LCD baru original selesai dan telah lulus uji QC 24 jam. Unit siap dikirimkan kembali ke alamat pelanggan.',
        ]);

        $claim->refresh();
        $this->assertEquals('repaired', $claim->status);
        $this->assertNotNull($claim->resolved_at);

        // Serial number harus dikembalikan ke status 'sold'
        $this->assertEquals('sold', $this->activeSerial->fresh()->status);
    }
}
