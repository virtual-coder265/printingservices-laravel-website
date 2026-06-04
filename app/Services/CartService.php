<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(): Cart
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        $sessionId = Session::getId();

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function add(Product $product, int $quantity = 1): CartItem
    {
        $cart = $this->getCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->update(['quantity' => $item->quantity + $quantity]);

            return $item->fresh('product');
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
        ])->load('product');
    }

    public function updateQuantity(CartItem $item, int $quantity): ?CartItem
    {
        if ($quantity <= 0) {
            $item->delete();

            return null;
        }

        $item->update(['quantity' => $quantity]);

        return $item->fresh('product');
    }

    public function remove(CartItem $item): void
    {
        $item->delete();
    }

    public function count(): int
    {
        return $this->getCart()->itemCount();
    }

    public function clear(): void
    {
        $this->getCart()->items()->delete();
    }

    public function mergeGuestCartOnLogin(): void
    {
        if (! Auth::check()) {
            return;
        }

        $sessionCart = Cart::where('session_id', Session::getId())->first();
        if (! $sessionCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($sessionCart->items as $guestItem) {
            $existing = $userCart->items()->where('product_id', $guestItem->product_id)->first();
            if ($existing) {
                $existing->update(['quantity' => $existing->quantity + $guestItem->quantity]);
            } else {
                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity' => $guestItem->quantity,
                ]);
            }
        }

        $sessionCart->items()->delete();
        $sessionCart->delete();
    }
}
