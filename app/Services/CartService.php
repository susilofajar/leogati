<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * Get or create the cart for current user or session.
     */
    public function getCart(?User $user = null): Cart
    {
        $user = $user ?? Auth::user();
        $sessionId = Session::getId();

        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        } else {
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        return $cart->load(['items.variant.product.brand', 'items.variant.product.primaryImage']);
    }

    /**
     * Add a product variant to the cart with stock validation.
     */
    public function addItem(int $variantId, int $quantity = 1): CartItem
    {
        $variant = ProductVariant::where('id', $variantId)
            ->where('is_active', true)
            ->firstOrFail();

        if ($variant->stock < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Stok produk tidak mencukupi. Sisa stok tersedia: ' . $variant->stock . ' unit.',
            ]);
        }

        $cart = $this->getCart();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($variant->stock < $newQuantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Jumlah di keranjang melebihi stok yang tersedia (' . $variant->stock . ' unit).',
                ]);
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_variant_id' => $variant->id,
                'quantity' => $quantity,
            ]);
        }

        return $cartItem;
    }

    /**
     * Update cart item quantity with stock validation.
     */
    public function updateItem(int $cartItemId, int $quantity): CartItem
    {
        if ($quantity <= 0) {
            $this->removeItem($cartItemId);
            throw ValidationException::withMessages([
                'quantity' => 'Jumlah barang harus minimal 1.',
            ]);
        }

        $cartItem = CartItem::findOrFail($cartItemId);
        $variant = $cartItem->variant;

        if ($variant->stock < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Jumlah melebihi stok yang tersedia (' . $variant->stock . ' unit).',
            ]);
        }

        $cartItem->update(['quantity' => $quantity]);

        return $cartItem;
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $cartItemId): void
    {
        CartItem::where('id', $cartItemId)->delete();
    }

    /**
     * Clear all items in cart.
     */
    public function clearCart(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }
}
