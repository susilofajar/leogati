<?php

namespace Tests\Feature;

use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\WarehouseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $warehouseStaff;

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
    }

    /**
     * Test admin can view inventory list and warehouse list.
     */
    public function test_admin_can_view_inventory_and_warehouse_list(): void
    {
        $responseWh = $this->actingAs($this->admin)->get('/admin/gudang');
        $responseWh->assertStatus(200);
        $responseWh->assertSee('Gudang Pusat LEOGATISTORE');

        $responseInv = $this->actingAs($this->admin)->get('/admin/inventaris');
        $responseInv->assertStatus(200);
        $responseInv->assertSee('Inventaris');
    }

    /**
     * Test warehouse staff can manually adjust stock and record movement.
     */
    public function test_warehouse_staff_can_adjust_stock_manually(): void
    {
        $variant = ProductVariant::first();
        $warehouse = Warehouse::first();
        $initialStock = $variant->stock;

        $response = $this->actingAs($this->warehouseStaff)->post('/admin/inventaris/' . $variant->id . '/sesuaikan', [
            'warehouse_id'    => $warehouse->id,
            'quantity_change' => 5,
            'notes'           => 'Penyesuaian stok hasil stock opname fisik',
        ]);

        $response->assertRedirect(route('admin.inventaris.mutasi', $variant->id));

        // Verifikasi mutasi tercatat
        $this->assertDatabaseHas('inventory_movements', [
            'product_variant_id' => $variant->id,
            'warehouse_id'       => $warehouse->id,
            'type'               => 'adjustment',
            'quantity_change'    => 5,
            'notes'              => 'Penyesuaian stok hasil stock opname fisik',
            'performed_by'       => $this->warehouseStaff->id,
        ]);

        // Verifikasi stok bertambah
        $variant->refresh();
        $this->assertEquals($initialStock + 5, $variant->stock);
    }

    /**
     * Test cannot adjust stock into negative quantity.
     */
    public function test_cannot_adjust_stock_to_negative_total(): void
    {
        $variant = ProductVariant::first();
        $warehouse = Warehouse::first();

        // Coba kurangi 9999 unit (melebihi stok)
        $response = $this->actingAs($this->warehouseStaff)->post('/admin/inventaris/' . $variant->id . '/sesuaikan', [
            'warehouse_id'    => $warehouse->id,
            'quantity_change' => -9999,
            'notes'           => 'Pengurangan ekstrim',
        ]);

        $response->assertSessionHasErrors('stock');
    }

    /**
     * Test inventory movement history page is accessible.
     */
    public function test_can_view_inventory_movement_history(): void
    {
        $variant = ProductVariant::first();
        $warehouse = Warehouse::first();

        // Buat 1 mutasi via service
        $inventoryService = app(InventoryService::class);
        $inventoryService->adjustStock(
            $variant,
            $warehouse,
            10,
            'purchase',
            null,
            'Uji coba penerimaan barang',
            $this->warehouseStaff
        );

        $response = $this->actingAs($this->admin)->get('/admin/inventaris/' . $variant->id . '/mutasi');
        $response->assertStatus(200);
        $response->assertSee('Uji coba penerimaan barang');
        $response->assertSee('Penerimaan Barang');
    }
}
