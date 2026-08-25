<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SerialNumber;
use Illuminate\Http\Request;

class SerialNumberController extends Controller
{
    /**
     * Daftar & pencarian nomor seri.
     */
    public function index(Request $request)
    {
        $query = SerialNumber::with([
            'productVariant.product',
            'warehouse',
            'customer',
            'purchaseOrder',
        ]);

        if ($search = $request->get('cari')) {
            $query->where('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('productVariant.product', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $serials = $query->latest()->paginate(30)->withQueryString();

        return view('admin.nomor-seri.index', compact('serials'));
    }

    /**
     * Detail riwayat 1 unit serialized.
     */
    public function show(SerialNumber $nomor_seri)
    {
        $nomor_seri->load([
            'productVariant.product',
            'warehouse',
            'purchaseOrder.supplier',
            'orderItem.order.user',
            'customer',
        ]);

        return view('admin.nomor-seri.show', compact('nomor_seri'));
    }
}
