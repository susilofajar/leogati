<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Tampilkan daftar wishlist pelanggan.
     */
    public function index()
    {
        $wishlists = Auth::user()
            ->wishlists()
            ->with(['product.brand', 'product.category', 'product.defaultVariant'])
            ->latest()
            ->paginate(12);

        return view('customer.wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle tambah/hapus produk dari wishlist (AJAX-friendly).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        $productId = $request->input('product_id');

        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $added = false;
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $added = true;
        }

        // Jika request AJAX, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'added' => $added,
                'count' => $user->wishlists()->count(),
            ]);
        }

        return back()->with('success', $added
            ? 'Produk berhasil ditambahkan ke Daftar Keinginan.'
            : 'Produk dihapus dari Daftar Keinginan.'
        );
    }

    /**
     * Hapus item dari wishlist.
     */
    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $wishlist->delete();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success']);
        }

        return back()->with('success', 'Produk dihapus dari Daftar Keinginan.');
    }
}
