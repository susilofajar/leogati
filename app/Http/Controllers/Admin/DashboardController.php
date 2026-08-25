<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\Models\WarrantyClaim;
use App\Services\ReportingService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private ReportingService $reporting) {}

    /**
     * Tampilkan dashboard operasional admin.
     */
    public function index(): View
    {
        // --- KPI Metrics ---
        $now = now();

        $totalRevenueBulanIni = $this->reporting->getTotalRevenue(
            $now->copy()->startOfMonth(),
            $now->copy()->endOfMonth()
        );

        $pesananHariIni = Order::whereDate('created_at', today())->count();

        $pendingOrders = Order::whereIn('status', ['pending', 'awaiting_payment', 'paid', 'processing'])->count();

        $stokRendah = \App\Models\ProductVariant::where('stock', '<=', 5)->count();

        $klaimAktif = WarrantyClaim::whereIn('status', ['submitted', 'reviewing', 'approved', 'in_repair'])->count();

        $ulasanMenunggu = Review::where('is_approved', false)->count();

        $totalPelanggan = User::whereHas('roles', fn($q) => $q->where('name', 'customer'))->count();

        $totalKategori = Category::count();

        $metrics = [
            'revenue_bulan_ini'  => $totalRevenueBulanIni,
            'pesanan_hari_ini'   => $pesananHariIni,
            'pending_orders'     => $pendingOrders,
            'stok_rendah'        => $stokRendah,
            'klaim_aktif'        => $klaimAktif,
            'ulasan_menunggu'    => $ulasanMenunggu,
            'total_pelanggan'    => $totalPelanggan,
            'total_kategori'     => $totalKategori,
        ];

        // --- Grafik Trend 7 Hari Terakhir ---
        $salesTrend = $this->reporting->getLast7DaysSales();
        $maxRevenue = $salesTrend->max('total_pendapatan') ?: 1;

        // --- Pesanan Terbaru ---
        $recentOrders = Order::with('user')
            ->latest()
            ->take(8)
            ->get();

        // --- Stok Kritis ---
        $criticalStock = $this->reporting->getLowStockReport(5)->take(8);

        return view('admin.dashboard', compact(
            'metrics',
            'salesTrend',
            'maxRevenue',
            'recentOrders',
            'criticalStock'
        ));
    }
}
