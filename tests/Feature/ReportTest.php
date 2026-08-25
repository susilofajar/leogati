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
use App\Services\ReportingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;
    protected ProductVariant $variant;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole    = Role::firstOrCreate(['name' => 'admin'], ['display_name' => 'Administrator']);
        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['display_name' => 'Pelanggan']);

        $this->admin = User::factory()->create(['name' => 'Admin Laporan']);
        $this->admin->roles()->attach($adminRole);

        $this->customer = User::factory()->create(['name' => 'Pembeli Setia']);
        $this->customer->roles()->attach($customerRole);

        Warehouse::create(['name' => 'Gudang Laporan', 'code' => 'GDG-RPT', 'is_default' => true]);

        $category = Category::create(['name' => 'Komponen PC', 'slug' => 'komponen-pc']);
        $brand    = Brand::create(['name' => 'AMD', 'slug' => 'amd']);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id'    => $brand->id,
            'name'        => 'AMD Ryzen 9 7900X',
            'slug'        => 'amd-ryzen-9-7900x',
            'status'      => 'active',
        ]);

        $this->variant = ProductVariant::create([
            'product_id' => $product->id,
            'name'       => 'Box, AM5 Socket',
            'sku'        => 'AMD-R9-7900X',
            'price'      => 7500000,
            'cost_price' => 6000000,
            'stock'      => 10,
        ]);

        // Buat pesanan completed
        $this->order = Order::create([
            'user_id'          => $this->customer->id,
            'order_number'     => 'LEO-RPT-001',
            'subtotal_amount'  => 7500000,
            'shipping_amount'  => 20000,
            'discount_amount'  => 0,
            'total_amount'     => 7520000,
            'status'           => 'completed',
            'payment_method'   => 'bca_va',
            'payment_status'   => 'paid',
            'shipping_courier' => 'jne',
            'shipping_service' => 'Reguler',
            'shipping_address' => ['recipient_name' => 'Pembeli Setia', 'phone_number' => '08123456789', 'address_line' => 'Jl. Test', 'city' => 'Jakarta', 'province' => 'DKI Jakarta', 'postal_code' => '12345'],
        ]);

        OrderItem::create([
            'order_id'           => $this->order->id,
            'product_variant_id' => $this->variant->id,
            'product_name'       => 'AMD Ryzen 9 7900X',
            'variant_name'       => 'Box, AM5 Socket',
            'sku'                => 'AMD-R9-7900X',
            'unit_price'         => 7500000,
            'quantity'           => 1,
            'subtotal'           => 7500000,
            'weight_grams'       => 600,
        ]);
    }

    public function test_admin_can_access_all_report_pages(): void
    {
        $this->actingAs($this->admin)->get(route('admin.laporan.penjualan'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.laporan.inventaris'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.laporan.pembelian'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('admin.laporan.pelanggan'))->assertStatus(200);
    }

    public function test_customer_cannot_access_report_pages(): void
    {
        $this->actingAs($this->customer)->get(route('admin.laporan.penjualan'))->assertStatus(403);
        $this->actingAs($this->customer)->get(route('admin.laporan.inventaris'))->assertStatus(403);
    }

    public function test_reporting_service_calculates_total_revenue(): void
    {
        $service = app(ReportingService::class);

        $revenue = $service->getTotalRevenue(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );

        // Pesanan selesai senilai 7,520,000 harus terhitung
        $this->assertEquals(7520000, $revenue);
    }

    public function test_reporting_service_sales_summary_returns_data(): void
    {
        $service = app(ReportingService::class);

        $summary = $service->getSalesSummary(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );

        $this->assertGreaterThan(0, $summary->count());
        $this->assertEquals(7520000, $summary->sum('total_pendapatan'));
    }

    public function test_reporting_service_top_selling_products(): void
    {
        $service = app(ReportingService::class);

        $topProducts = $service->getTopSellingProducts(10);

        $this->assertGreaterThan(0, $topProducts->count());
        $this->assertEquals('AMD-R9-7900X', $topProducts->first()->sku);
        $this->assertEquals(1, $topProducts->first()->total_qty);
    }

    public function test_reporting_service_low_stock_report(): void
    {
        // Set stok varian menjadi 3 (di bawah threshold 5)
        $this->variant->update(['stock' => 3]);

        $service   = app(ReportingService::class);
        $lowStock  = $service->getLowStockReport(5);

        $this->assertTrue($lowStock->contains('id', $this->variant->id));
    }

    public function test_reporting_service_top_customers(): void
    {
        $service      = app(ReportingService::class);
        $topCustomers = $service->getTopCustomers(10);

        $this->assertGreaterThan(0, $topCustomers->count());
        $this->assertEquals($this->customer->id, $topCustomers->first()->id);
        $this->assertEquals(7520000, $topCustomers->first()->total_belanja);
    }

    public function test_admin_dashboard_renders_with_real_metrics(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        // Dashboard harus menampilkan elemen KPI
        $response->assertSee('Pendapatan Bulan Ini');
        $response->assertSee('Pesanan Hari Ini');
        $response->assertSee('Stok Kritis');
    }

    public function test_sales_report_with_date_filter(): void
    {
        $from = Carbon::now()->startOfMonth()->toDateString();
        $to   = Carbon::now()->endOfMonth()->toDateString();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.laporan.penjualan', ['dari' => $from, 'sampai' => $to]));

        $response->assertStatus(200);
        $response->assertSee(rupiah(7520000));
    }
}
