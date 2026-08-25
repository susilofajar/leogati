<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\ReportingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private ReportingService $reporting) {}

    /**
     * Laporan Penjualan.
     */
    public function penjualan(Request $request): View
    {
        $from = $request->filled('dari')
            ? Carbon::parse($request->dari)
            : Carbon::now()->startOfMonth();

        $to = $request->filled('sampai')
            ? Carbon::parse($request->sampai)
            : Carbon::now()->endOfMonth();

        $salesSummary   = $this->reporting->getSalesSummary($from, $to);
        $topProducts    = $this->reporting->getTopSellingProducts(10, $from, $to);
        $salesByCategory = $this->reporting->getSalesByCategory($from, $to);
        $salesByBrand   = $this->reporting->getSalesByBrand($from, $to);
        $totalRevenue   = $this->reporting->getTotalRevenue($from, $to);
        $monthlySales   = $this->reporting->getMonthlySales(Carbon::now()->year);

        return view('admin.laporan.penjualan', compact(
            'from', 'to',
            'salesSummary',
            'topProducts',
            'salesByCategory',
            'salesByBrand',
            'totalRevenue',
            'monthlySales'
        ));
    }

    /**
     * Laporan Inventaris & Stok.
     */
    public function inventaris(Request $request): View
    {
        $warehouses      = Warehouse::all();
        $lowStock        = $this->reporting->getLowStockReport(5);
        $deadStock       = $this->reporting->getDeadStockReport(90);

        $movementFilters = $request->only(['variant_id', 'type', 'warehouse_id', 'from', 'to']);
        $movements       = $this->reporting->getStockMovementReport($movementFilters)->paginate(20)->withQueryString();

        return view('admin.laporan.inventaris', compact(
            'warehouses',
            'lowStock',
            'deadStock',
            'movements',
            'movementFilters'
        ));
    }

    /**
     * Laporan Pembelian & Supplier.
     */
    public function pembelian(Request $request): View
    {
        $suppliers       = Supplier::where('is_active', true)->orderBy('name')->get();
        $supplierAnalysis = $this->reporting->getSupplierAnalysis();

        $poFilters = $request->only(['supplier_id', 'status', 'from', 'to']);
        $purchaseOrders = $this->reporting->getPurchaseReport($poFilters)->paginate(20)->withQueryString();

        return view('admin.laporan.pembelian', compact(
            'suppliers',
            'supplierAnalysis',
            'purchaseOrders',
            'poFilters'
        ));
    }

    /**
     * Laporan Pelanggan.
     */
    public function pelanggan(Request $request): View
    {
        $from = $request->filled('dari')
            ? Carbon::parse($request->dari)
            : Carbon::now()->startOfMonth();

        $to = $request->filled('sampai')
            ? Carbon::parse($request->sampai)
            : Carbon::now()->endOfMonth();

        $newCustomers = $this->reporting->getNewCustomers($from, $to);
        $topCustomers = $this->reporting->getTopCustomers(10);

        return view('admin.laporan.pelanggan', compact(
            'from', 'to',
            'newCustomers',
            'topCustomers'
        ));
    }
}
