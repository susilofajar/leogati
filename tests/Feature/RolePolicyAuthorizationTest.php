<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePolicyAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $admin;
    protected User $warehouseStaff;
    protected User $salesStaff;
    protected User $financeStaff;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);

        $this->superAdmin = User::where('email', 'superadmin@leogati.store')->first();
        $this->admin = User::where('email', 'admin@leogati.store')->first();
        $this->warehouseStaff = User::where('email', 'gudang@leogati.store')->first();
        $this->salesStaff = User::where('email', 'sales@leogati.store')->first();
        $this->financeStaff = User::where('email', 'finance@leogati.store')->first();
        $this->customer = User::where('email', 'pelanggan@example.com')->first();
    }

    /**
     * 1. Test Super Admin can access all admin features.
     */
    public function test_super_admin_has_full_access(): void
    {
        $this->actingAs($this->superAdmin);

        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.produk.index'))->assertStatus(200);
        $this->get(route('admin.kategori.index'))->assertStatus(200);
        $this->get(route('admin.merek.index'))->assertStatus(200);
        $this->get(route('admin.pesanan.index'))->assertStatus(200);
        $this->get(route('admin.inventaris.index'))->assertStatus(200);
        $this->get(route('admin.gudang.index'))->assertStatus(200);
        $this->get(route('admin.nomor_seri.index'))->assertStatus(200);
        $this->get(route('admin.supplier.index'))->assertStatus(200);
        $this->get(route('admin.pembelian.index'))->assertStatus(200);
        $this->get(route('admin.garansi.index'))->assertStatus(200);
        $this->get(route('admin.kupon.index'))->assertStatus(200);
        $this->get(route('admin.ulasan.index'))->assertStatus(200);
        $this->get(route('admin.laporan.penjualan'))->assertStatus(200);
        $this->get(route('admin.pengguna.index'))->assertStatus(200);
        $this->get(route('admin.audit_log.index'))->assertStatus(200);
    }

    /**
     * 2. Test Admin can manage products, categories, brands, orders, promotions, reviews, reports,
     *    and operations, while specialized staff roles are strictly restricted.
     */
    public function test_admin_access_boundaries(): void
    {
        $this->actingAs($this->admin);

        // Allowed
        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.produk.index'))->assertStatus(200);
        $this->get(route('admin.kategori.index'))->assertStatus(200);
        $this->get(route('admin.merek.index'))->assertStatus(200);
        $this->get(route('admin.pesanan.index'))->assertStatus(200);
        $this->get(route('admin.garansi.index'))->assertStatus(200);
        $this->get(route('admin.kupon.index'))->assertStatus(200);
        $this->get(route('admin.ulasan.index'))->assertStatus(200);
        $this->get(route('admin.laporan.penjualan'))->assertStatus(200);
        $this->get(route('admin.pengguna.index'))->assertStatus(200);
        $this->get(route('admin.supplier.index'))->assertStatus(200);
        $this->get(route('admin.audit_log.index'))->assertStatus(200);
    }

    /**
     * 3. Test Warehouse Staff can manage inventory, warehouses, serial numbers, warranty claims,
     *    can view products/orders, but CANNOT create products, categories, brands, coupons, users.
     */
    public function test_warehouse_staff_access_boundaries(): void
    {
        $this->actingAs($this->warehouseStaff);

        // Allowed
        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.inventaris.index'))->assertStatus(200);
        $this->get(route('admin.gudang.index'))->assertStatus(200);
        $this->get(route('admin.nomor_seri.index'))->assertStatus(200);
        $this->get(route('admin.garansi.index'))->assertStatus(200);
        $this->get(route('admin.produk.index'))->assertStatus(200); // Read only
        $this->get(route('admin.pesanan.index'))->assertStatus(200); // Read for packing

        // Forbidden
        $this->get(route('admin.produk.create'))->assertStatus(403);
        $this->get(route('admin.kategori.index'))->assertStatus(403);
        $this->get(route('admin.merek.index'))->assertStatus(403);
        $this->get(route('admin.kupon.index'))->assertStatus(403);
        $this->get(route('admin.ulasan.index'))->assertStatus(403);
        $this->get(route('admin.supplier.index'))->assertStatus(403);
        $this->get(route('admin.pengguna.index'))->assertStatus(403);
        $this->get(route('admin.audit_log.index'))->assertStatus(403);
        $this->get(route('admin.laporan.penjualan'))->assertStatus(403);
    }

    /**
     * 4. Test Sales Staff can manage orders and promotions, view products,
     *    but CANNOT manage inventory, categories, brands, suppliers, users.
     */
    public function test_sales_staff_access_boundaries(): void
    {
        $this->actingAs($this->salesStaff);

        // Allowed
        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.pesanan.index'))->assertStatus(200);
        $this->get(route('admin.kupon.index'))->assertStatus(200);
        $this->get(route('admin.produk.index'))->assertStatus(200); // Read only

        // Forbidden
        $this->get(route('admin.produk.create'))->assertStatus(403);
        $this->get(route('admin.kategori.index'))->assertStatus(403);
        $this->get(route('admin.merek.index'))->assertStatus(403);
        $this->get(route('admin.inventaris.index'))->assertStatus(403);
        $this->get(route('admin.gudang.index'))->assertStatus(403);
        $this->get(route('admin.nomor_seri.index'))->assertStatus(403);
        $this->get(route('admin.supplier.index'))->assertStatus(403);
        $this->get(route('admin.pengguna.index'))->assertStatus(403);
        $this->get(route('admin.audit_log.index'))->assertStatus(403);
        $this->get(route('admin.laporan.penjualan'))->assertStatus(403);
    }

    /**
     * 5. Test Finance Staff can manage orders and financial reports,
     *    but CANNOT access inventory, products CRUD, categories, brands, suppliers, users.
     */
    public function test_finance_staff_access_boundaries(): void
    {
        $this->actingAs($this->financeStaff);

        // Allowed
        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.pesanan.index'))->assertStatus(200);
        $this->get(route('admin.laporan.penjualan'))->assertStatus(200);
        $this->get(route('admin.laporan.pelanggan'))->assertStatus(200);

        // Forbidden
        $this->get(route('admin.produk.index'))->assertStatus(403);
        $this->get(route('admin.kategori.index'))->assertStatus(403);
        $this->get(route('admin.merek.index'))->assertStatus(403);
        $this->get(route('admin.inventaris.index'))->assertStatus(403);
        $this->get(route('admin.gudang.index'))->assertStatus(403);
        $this->get(route('admin.nomor_seri.index'))->assertStatus(403);
        $this->get(route('admin.kupon.index'))->assertStatus(403);
        $this->get(route('admin.supplier.index'))->assertStatus(403);
        $this->get(route('admin.pengguna.index'))->assertStatus(403);
        $this->get(route('admin.audit_log.index'))->assertStatus(403);
        $this->get(route('admin.laporan.inventaris'))->assertStatus(403);
        $this->get(route('admin.laporan.pembelian'))->assertStatus(403);
    }

    /**
     * 6. Test Customer CANNOT access any admin route.
     */
    public function test_customer_cannot_access_any_admin_route(): void
    {
        $this->actingAs($this->customer);

        $this->get(route('admin.dashboard'))->assertStatus(403);
        $this->get(route('admin.produk.index'))->assertStatus(403);
        $this->get(route('admin.pesanan.index'))->assertStatus(403);
        $this->get(route('admin.inventaris.index'))->assertStatus(403);
        $this->get(route('admin.pengguna.index'))->assertStatus(403);
    }
}
