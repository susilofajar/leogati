<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    /**
     * Tampilkan halaman perbandingan produk.
     */
    public function index(Request $request)
    {
        $compareIds = session('compare_products', []);
        $products = collect();

        if (!empty($compareIds)) {
            $products = Product::with([
                'brand',
                'category',
                'defaultVariant',
                'specifications.specificationAttribute.group',
            ])
            ->whereIn('id', $compareIds)
            ->get();
        }

        // Kumpulkan semua grup spesifikasi dari produk yang dibandingkan
        $specGroups = [];
        foreach ($products as $product) {
            foreach ($product->specifications as $spec) {
                $group = $spec->specificationAttribute->group->name ?? 'Lainnya';
                $attr = $spec->specificationAttribute->name ?? '';
                if (!isset($specGroups[$group])) {
                    $specGroups[$group] = [];
                }
                if (!in_array($attr, $specGroups[$group])) {
                    $specGroups[$group][] = $attr;
                }
            }
        }

        return view('products.compare', compact('products', 'specGroups'));
    }

    /**
     * Tambahkan produk ke daftar perbandingan (session, maks 4).
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $compareIds = session('compare_products', []);
        $productId = (int) $request->input('product_id');

        if (count($compareIds) >= 4) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maksimal 4 produk dapat dibandingkan.',
                ], 422);
            }
            return back()->with('error', 'Maksimal 4 produk dapat dibandingkan.');
        }

        if (!in_array($productId, $compareIds)) {
            $compareIds[] = $productId;
            session(['compare_products' => $compareIds]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'count' => count($compareIds),
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke daftar perbandingan.');
    }

    /**
     * Hapus produk dari daftar perbandingan.
     */
    public function remove(Request $request, $id)
    {
        $compareIds = session('compare_products', []);
        $compareIds = array_values(array_filter($compareIds, fn($v) => $v != (int) $id));
        session(['compare_products' => $compareIds]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'count' => count($compareIds),
            ]);
        }

        return back()->with('success', 'Produk dihapus dari daftar perbandingan.');
    }
}
