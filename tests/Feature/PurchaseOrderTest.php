<?php

namespace Tests\Feature;

use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\WarehouseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $warehouseStaff;
    protected Supplier $supplier;
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
        $this->warehouseStaff = User::where('email', 'gudang@leogati.store')->first();
        $this->warehouse = Warehouse::first();

        $this->supplier = Supplier::create([
            'code'          => 'SUP-0001',
            'name'          => 'PT Distributor Asus Indonesia',
            'pic_name'      => 'Hendro Wijaya',
            'email'         => 'hendro@asusdistro.co.id',
            'phone'         => '081122334455',
            'city'          => 'Jakarta Utara',
            'payment_terms' => 'NET30',
            'is_active'     => true,
        ]);
    }

    /**
     * Test admin can create supplier and view supplier list.
     */
    public function test_can_manage_suppliers(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/supplier');
        $response->assertStatus(200);
        $response->assertSee('PT Distributor Asus Indonesia');

        // Create new supplier
        $createResponse = $this->actingAs($this->admin)->post('/admin/supplier', [
            'name'          => 'PT Gigabyte Jaya',
            'pic_name'      => 'Anto',
            'email'         => 'anto@gigabyte.id',
            'phone'         => '081299887766',
            'city'          => 'Surabaya',
            'payment_terms' => 'COD',
            'is_active'     => 1,
        ]);

        $createResponse->assertRedirect(route('admin.supplier.index'));
        $this->assertDatabaseHas('suppliers', [
            'name' => 'PT Gigabyte Jaya',
            'city' => 'Surabaya',
        ]);
    }

    /**
     * Test creating a purchase order and receiving goods into inventory.
     */
    public function test_create_po_and_receive_goods_successfully(): void
    {
        $variant = ProductVariant::first();
        $variant->update(['is_serialized' => true]);
        $initialStock = $variant->stock;

        // 1. Buat Purchase Order
        $poResponse = $this->actingAs($this->admin)->post('/admin/pembelian', [
            'supplier_id'  => $this->supplier->id,
            'warehouse_id' => $this->warehouse->id,
            'expected_at'  => now()->addDays(7)->format('Y-m-d'),
            'notes'        => 'Pengadaan unit laptop baru',
            'items'        => [
                [
                    'product_variant_id' => $variant->id,
                    'quantity_ordered'   => 2,
                    'unit_cost'          => 15000000,
                ],
            ],
        ]);

        $po = PurchaseOrder::where('supplier_id', $this->supplier->id)->first();
        $this->assertNotNull($po);
        $poResponse->assertRedirect(route('admin.pembelian.show', $po->id));
        $this->assertEquals('draft', $po->status);
        $this->assertEquals(30000000, $po->total_amount);

        // 2. Tandai PO Dikirim ke Supplier
        $sentResponse = $this->actingAs($this->admin)->post('/admin/pembelian/' . $po->id . '/kirim');
        $sentResponse->assertRedirect();
        $po->refresh();
        $this->assertEquals('sent', $po->status);

        // 3. Terima Barang di Gudang + Registrasi 2 Nomor Seri
        $poItem = $po->items->first();
        $receiveResponse = $this->actingAs($this->warehouseStaff)->post('/admin/pembelian/' . $po->id . '/terima', [
            'items' => [
                [
                    'po_item_id'        => $poItem->id,
                    'quantity_received' => 2,
                    'serial_numbers'    => "SN-TEST-001\nSN-TEST-002",
                    'warranty_months'   => 24,
                ],
            ],
        ]);

        $receiveResponse->assertRedirect(route('admin.pembelian.show', $po->id));

        // Verifikasi Status PO menjadi 'received'
        $po->refresh();
        $this->assertEquals('received', $po->status);

        // Verifikasi Stok Varian Bertambah
        $variant->refresh();
        $this->assertEquals($initialStock + 2, $variant->stock);

        // Verifikasi 2 Nomor Seri Terdaftar
        $this->assertDatabaseHas('serial_numbers', [
            'serial_number'      => 'SN-TEST-001',
            'product_variant_id' => $variant->id,
            'purchase_order_id'  => $po->id,
            'status'             => 'available',
        ]);
        $this->assertDatabaseHas('serial_numbers', [
            'serial_number'      => 'SN-TEST-002',
            'product_variant_id' => $variant->id,
            'purchase_order_id'  => $po->id,
            'status'             => 'available',
        ]);

        // Verifikasi Mutasi Stok Tercatat
        $this->assertDatabaseHas('inventory_movements', [
            'product_variant_id' => $variant->id,
            'warehouse_id'       => $this->warehouse->id,
            'type'               => 'purchase',
            'quantity_change'    => 2,
        ]);
    }
}
