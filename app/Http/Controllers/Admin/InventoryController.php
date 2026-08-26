<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdjustInventoryRequest;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Daftar stok semua varian produk.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Inventory::class);

        $query = ProductVariant::with(['product', 'inventoryRecords.warehouse'])
            ->whereHas('product', fn($q) => $q->where('is_active', true));

        // Filter pencarian
        if ($search = $request->get('cari')) {
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhereHas('product', fn($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter stok rendah (di bawah 5)
        if ($request->get('stok_rendah')) {
            $query->where('stock', '<', 5);
        }

        $variants = $query->orderBy('stock', 'asc')->paginate(30)->withQueryString();
        $warehouses = Warehouse::where('is_active', true)->get();

        return view('admin.inventaris.index', compact('variants', 'warehouses'));
    }

    /**
     * Riwayat mutasi stok untuk 1 varian.
     */
    public function movements(ProductVariant $varian)
    {
        $this->authorize('viewAny', Inventory::class);

        $movements = InventoryMovement::with(['warehouse', 'performer'])
            ->where('product_variant_id', $varian->id)
            ->latest()
            ->paginate(30);

        return view('admin.inventaris.mutasi', compact('varian', 'movements'));
    }

    /**
     * Form penyesuaian stok manual.
     */
    public function adjustForm(ProductVariant $varian)
    {
        $this->authorize('adjust', Inventory::class);

        $warehouses = Warehouse::where('is_active', true)->get();
        return view('admin.inventaris.sesuaikan', compact('varian', 'warehouses'));
    }

    /**
     * Proses penyesuaian stok manual.
     */
    public function adjust(AdjustInventoryRequest $request, ProductVariant $varian)
    {
        $this->authorize('adjust', Inventory::class);

        $warehouse = Warehouse::findOrFail($request->warehouse_id);

        try {
            $this->inventoryService->adjustStock(
                $varian,
                $warehouse,
                (int) $request->quantity_change,
                'adjustment',
                null,
                $request->notes,
                $request->user()
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        $arah = $request->quantity_change > 0 ? 'ditambah' : 'dikurangi';
        return redirect()->route('admin.inventaris.mutasi', $varian->id)
            ->with('success', "Stok berhasil {$arah} sebanyak " . abs($request->quantity_change) . " unit.");
    }
}
