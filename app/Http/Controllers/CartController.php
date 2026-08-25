<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CouponService $couponService
    ) {}

    /**
     * Display shopping cart page with coupon discount calculation.
     */
    public function index(): View
    {
        $cart = $this->cartService->getCart();
        $appliedCoupon = null;
        $discountAmount = 0;

        if ($code = session('applied_coupon')) {
            try {
                $appliedCoupon = $this->couponService->validateCoupon($code, $cart->subtotal);
                $discountAmount = $this->couponService->calculateDiscount($appliedCoupon, $cart->subtotal);
            } catch (ValidationException $e) {
                // Kupon sudah tidak valid (misal subtotal berkurang di bawah min purchase)
                session()->forget('applied_coupon');
            }
        }

        return view('cart.index', compact('cart', 'appliedCoupon', 'discountAmount'));
    }

    /**
     * Add product variant to shopping cart.
     */
    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ], [], [
            'product_variant_id' => 'varian produk',
            'quantity' => 'jumlah barang',
        ]);

        $this->cartService->addItem(
            (int) $request->input('product_variant_id'),
            (int) $request->input('quantity', 1)
        );

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang belanja Anda.');
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ], [], [
            'quantity' => 'jumlah barang',
        ]);

        $this->cartService->updateItem($id, (int) $request->input('quantity'));

        return redirect()->route('cart.index')
            ->with('success', 'Jumlah produk di keranjang belanja berhasil diperbarui.');
    }

    /**
     * Remove item from cart.
     */
    public function remove(int $id): RedirectResponse
    {
        $this->cartService->removeItem($id);

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang belanja.');
    }

    /**
     * Clear all items in cart.
     */
    public function clear(): RedirectResponse
    {
        $this->cartService->clearCart();
        session()->forget('applied_coupon');

        return redirect()->route('cart.index')
            ->with('success', 'Keranjang belanja Anda telah dikosongkan.');
    }

    /**
     * Gunakan kode kupon promosi.
     */
    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate([
            'coupon_code' => ['required', 'string'],
        ], [
            'coupon_code.required' => 'Masukkan kode kupon promosi.',
        ]);

        $cart = $this->cartService->getCart();

        $coupon = $this->couponService->validateCoupon($request->input('coupon_code'), $cart->subtotal);

        session(['applied_coupon' => $coupon->code]);

        return redirect()->route('cart.index')
            ->with('success', "Kupon promo '{$coupon->code}' berhasil diterapkan! Potongan: " . $coupon->type_label);
    }

    /**
     * Hapus kupon yang sedang digunakan.
     */
    public function removeCoupon(): RedirectResponse
    {
        session()->forget('applied_coupon');

        return redirect()->route('cart.index')
            ->with('info', 'Kupon promo telah dihapus dari keranjang belanja.');
    }
}

