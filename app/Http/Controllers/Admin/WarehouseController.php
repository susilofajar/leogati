<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    /**
     * Daftar semua gudang beserta ringkasan stok.
     */
    public function index()
    {
        $warehouses = Warehouse::withCount('inventory as total_skus')
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.gudang.index', compact('warehouses'));
    }

    /**
     * Detail stok di gudang tertentu.
     */
    public function show(Warehouse $gudang)
    {
        $inventoryItems = Inventory::with(['productVariant.product'])
            ->where('warehouse_id', $gudang->id)
            ->paginate(30);

        return view('admin.gudang.show', compact('gudang', 'inventoryItems'));
    }
}
