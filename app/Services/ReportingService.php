<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportingService
{
    /**
     * Ambil ringkasan penjualan harian dalam rentang tanggal.
     */
    public function getSalesSummary(Carbon $from, Carbon $to): Collection
    {
        return Order::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total_pesanan, SUM(total_amount) as total_pendapatan')
            ->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
            ->whereBetween('created_at', [$from->startOfDay(), $to->copy()->endOfDay()])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('tanggal')
            ->get();
    }

    /**
     * Ambil penjualan bulanan untuk satu tahun.
     */
    public function getMonthlySales(int $year): Collection
    {
        $driver = config('database.connections.' . config('database.default') . '.driver');

        if ($driver === 'sqlite') {
            return Order::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as bulan, COUNT(*) as total_pesanan, SUM(total_amount) as total_pendapatan")
                ->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
                ->whereRaw("strftime('%Y', created_at) = ?", [(string) $year])
                ->groupByRaw("strftime('%m', created_at)")
                ->orderBy('bulan')
                ->get();
        }

        return Order::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total_pesanan, SUM(total_amount) as total_pendapatan')
            ->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->orderBy('bulan')
            ->get();
    }

    /**
     * Ambil trend penjualan 7 hari terakhir (untuk chart dashboard).
     */
    public function getLast7DaysSales(): Collection
    {
        $from = Carbon::now()->subDays(6)->startOfDay();
        $to   = Carbon::now()->endOfDay();

        $raw = Order::selectRaw('DATE(created_at) as tanggal, SUM(total_amount) as total_pendapatan')
            ->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
            ->whereBetween('created_at', [$from, $to])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('tanggal')
            ->pluck('total_pendapatan', 'tanggal');

        // Lengkapi semua 7 hari meskipun tidak ada data
        $result = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $result->push([
                'tanggal'            => $date,
                'label'              => Carbon::parse($date)->translatedFormat('D'),
                'total_pendapatan'   => $raw->get($date, 0),
            ]);
        }

        return $result;
    }

    /**
     * Ambil distribusi status pesanan dalam rentang tanggal.
     */
    public function getOrderStatusDistribution(Carbon $from, Carbon $to): Collection
    {
        return Order::selectRaw('status, COUNT(*) as jumlah')
            ->whereBetween('created_at', [$from->startOfDay(), $to->copy()->endOfDay()])
            ->groupBy('status')
            ->orderByRaw('COUNT(*) DESC')
            ->get();
    }

    /**
     * Ambil TOP N produk terlaris berdasarkan qty terjual.
     */
    public function getTopSellingProducts(int $limit = 10, ?Carbon $from = null, ?Carbon $to = null): Collection
    {
        $query = OrderItem::selectRaw('
                product_variant_id,
                SUM(quantity) as total_qty,
                SUM(subtotal) as total_revenue,
                MIN(product_name) as product_name,
                MIN(variant_name) as variant_name,
                MIN(sku) as sku
            ')
            ->whereHas('order', function ($q) use ($from, $to) {
                $q->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed']);
                if ($from && $to) {
                    $q->whereBetween('created_at', [$from->startOfDay(), $to->copy()->endOfDay()]);
                }
            })
            ->groupBy('product_variant_id')
            ->orderByRaw('SUM(quantity) DESC')
            ->limit($limit);

        return $query->get();
    }

    /**
     * Ambil penjualan per kategori produk.
     */
    public function getSalesByCategory(Carbon $from, Carbon $to): Collection
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name as kategori, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue')
            ->whereIn('orders.status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
            ->whereBetween('orders.created_at', [$from->startOfDay(), $to->copy()->endOfDay()])
            ->groupBy('categories.id', 'categories.name')
            ->orderByRaw('SUM(order_items.subtotal) DESC')
            ->get();
    }

    /**
     * Ambil penjualan per merek produk.
     */
    public function getSalesByBrand(Carbon $from, Carbon $to): Collection
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->selectRaw('brands.name as merek, SUM(order_items.quantity) as total_qty, SUM(order_items.subtotal) as total_revenue')
            ->whereIn('orders.status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
            ->whereBetween('orders.created_at', [$from->startOfDay(), $to->copy()->endOfDay()])
            ->groupBy('brands.id', 'brands.name')
            ->orderByRaw('SUM(order_items.subtotal) DESC')
            ->get();
    }

    /**
     * Ambil laporan stok inventaris saat ini.
     */
    public function getInventoryReport(): Collection
    {
        return ProductVariant::with(['product.category', 'product.brand'])
            ->select('product_variants.*')
            ->selectRaw('(product_variants.stock * product_variants.cost_price) as nilai_stok')
            ->orderBy('stock', 'asc')
            ->get();
    }

    /**
     * Ambil varian produk dengan stok di bawah threshold.
     */
    public function getLowStockReport(int $threshold = 5): Collection
    {
        return ProductVariant::with(['product.category', 'product.brand'])
            ->where('stock', '<=', $threshold)
            ->orderBy('stock', 'asc')
            ->get();
    }

    /**
     * Ambil produk yang tidak terjual dalam X hari terakhir (dead stock).
     */
    public function getDeadStockReport(int $days = 90): Collection
    {
        $cutoff = Carbon::now()->subDays($days);

        $soldVariantIds = OrderItem::whereHas('order', function ($q) use ($cutoff) {
            $q->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
              ->where('created_at', '>=', $cutoff);
        })->pluck('product_variant_id')->unique();

        return ProductVariant::with(['product.category', 'product.brand'])
            ->where('stock', '>', 0)
            ->whereNotIn('id', $soldVariantIds)
            ->orderBy('stock', 'desc')
            ->get();
    }

    /**
     * Ambil riwayat mutasi stok dengan filter.
     */
    public function getStockMovementReport(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = InventoryMovement::with(['productVariant.product', 'warehouse', 'performer'])
            ->latest();

        if (!empty($filters['variant_id'])) {
            $query->where('product_variant_id', $filters['variant_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['warehouse_id'])) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (!empty($filters['from']) && !empty($filters['to'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($filters['from'])->startOfDay(),
                Carbon::parse($filters['to'])->endOfDay(),
            ]);
        }

        return $query;
    }

    /**
     * Ambil riwayat purchase orders dengan filter.
     */
    public function getPurchaseReport(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = PurchaseOrder::with(['supplier', 'items'])
            ->latest('created_at');

        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from'])) {
            $query->whereDate(
                'created_at',
                '>=',
                Carbon::parse($filters['from'])->toDateString()
            );
        }

        if (!empty($filters['to'])) {
            $query->whereDate(
                'created_at',
                '<=',
                Carbon::parse($filters['to'])->toDateString()
            );
        }

        return $query;
    }

    /**
     * Ambil analisis pengeluaran per supplier.
     */
    public function getSupplierAnalysis(): Collection
    {
        return DB::table('purchase_orders')
            ->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
            ->selectRaw('suppliers.id, suppliers.name as supplier, COUNT(purchase_orders.id) as total_po, SUM(purchase_orders.total_amount) as total_nilai')
            ->whereNotIn('purchase_orders.status', ['cancelled'])
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByRaw('SUM(purchase_orders.total_amount) DESC')
            ->get();
    }

    /**
     * Ambil pelanggan baru dalam rentang tanggal.
     */
    public function getNewCustomers(Carbon $from, Carbon $to): Collection
    {
        return User::whereHas('roles', fn($q) => $q->where('name', 'customer'))
            ->whereBetween('created_at', [$from->startOfDay(), $to->copy()->endOfDay()])
            ->withCount(['orders as total_pesanan' => fn($q) =>
                $q->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Ambil TOP N pelanggan berdasarkan total belanja.
     */
    public function getTopCustomers(int $limit = 10): Collection
    {
        return User::whereHas('roles', fn($q) => $q->where('name', 'customer'))
            ->withSum(['orders as total_belanja' => fn($q) =>
                $q->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
            ], 'total_amount')
            ->withCount(['orders as total_pesanan' => fn($q) =>
                $q->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
            ])
            ->orderByDesc('total_belanja')
            ->limit($limit)
            ->get();
    }

    /**
     * Hitung total pendapatan dalam periode tertentu.
     */
    public function getTotalRevenue(?Carbon $from = null, ?Carbon $to = null): float
    {
        $query = Order::whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed']);
        if ($from && $to) {
            $query->whereBetween('created_at', [$from->startOfDay(), $to->copy()->endOfDay()]);
        }
        return (float) $query->sum('total_amount');
    }

    /**
     * Get sales report data for background job processing
     */
    public function getSalesReport(string $startDate, string $endDate): array
    {
        $from = Carbon::parse($startDate)->startOfDay();
        $to = Carbon::parse($endDate)->endOfDay();

        return Order::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue')
            ->whereIn('status', ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'])
            ->whereBetween('created_at', [$from, $to])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'orders' => $item->orders,
                    'revenue' => (float) $item->revenue,
                ];
            })
            ->toArray();
    }
}
