<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($search = $request->get('cari')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if ($request->has('aktif')) {
            $query->where('is_active', (bool) $request->get('aktif'));
        }

        $suppliers = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.supplier.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $data = $request->validated();
        $data['code'] = Supplier::generateCode();
        $data['is_active'] = $request->boolean('is_active', true);

        Supplier::create($data);

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('purchaseOrders.warehouse');
        return view('admin.supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(StoreSupplierRequest $request, Supplier $supplier)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $supplier->update($data);

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        // Cegah penghapusan jika supplier memiliki PO
        if ($supplier->purchaseOrders()->exists()) {
            return back()->withErrors(['supplier' => 'Supplier tidak dapat dihapus karena memiliki riwayat Purchase Order.']);
        }

        $supplier->delete();

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
