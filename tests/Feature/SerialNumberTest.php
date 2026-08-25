<?php

namespace Tests\Feature;

use App\Models\ProductVariant;
use App\Models\SerialNumber;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\WarehouseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SerialNumberTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected Warehouse $warehouse;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RoleAndPermissionSeeder::class,
            CatalogBaseSeeder::class,
            ProductCatalogSeeder::class,
            WarehouseSeeder::class,
        ]);

        $this->admin = User::where('email', 'admin@leogati.store')->first();
        $this->customer = User::where('email', 'pelanggan@example.com')->first();
        $this->warehouse = Warehouse::first();
    }

    /**
     * Test admin can view serial numbers list and detail page.
     */
    public function test_admin_can_view_serial_number_list_and_detail(): void
    {
        $variant = ProductVariant::first();

        $serial = SerialNumber::create([
            'serial_number'       => 'SN-ASUS-99999',
            'product_variant_id'  => $variant->id,
            'warehouse_id'        => $this->warehouse->id,
            'status'              => 'available',
            'purchased_at'        => now()->subDays(10),
            'warranty_expires_at' => now()->addYears(2),
        ]);

        $responseList = $this->actingAs($this->admin)->get('/admin/nomor-seri');
        $responseList->assertStatus(200);
        $responseList->assertSee('SN-ASUS-99999');

        $responseDetail = $this->actingAs($this->admin)->get('/admin/nomor-seri/' . $serial->id);
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('SN-ASUS-99999');
        $responseDetail->assertSee('Informasi Produk');
    }

    /**
     * Test storefront warranty lookup page can find real registered serial number.
     */
    public function test_public_warranty_check_with_registered_serial_number(): void
    {
        $variant = ProductVariant::first();

        SerialNumber::create([
            'serial_number'       => 'SN-ROG-VALID-123',
            'product_variant_id'  => $variant->id,
            'warehouse_id'        => $this->warehouse->id,
            'status'              => 'sold',
            'sold_at'             => now()->subMonths(3),
            'warranty_expires_at' => now()->addMonths(21),
        ]);

        // Cari nomor seri yang terdaftar
        $responseFound = $this->get('/garansi/cek?sn=SN-ROG-VALID-123');
        $responseFound->assertStatus(200);
        $responseFound->assertSee('Garansi Resmi Terdaftar');
        $responseFound->assertSee('SN-ROG-VALID-123');

        // Cari nomor seri yang tidak terdaftar
        $responseNotFound = $this->get('/garansi/cek?sn=SN-RANDOM-NOT-FOUND');
        $responseNotFound->assertStatus(200);
        $responseNotFound->assertSee('Nomor Seri Tidak Ditemukan');
    }
}
