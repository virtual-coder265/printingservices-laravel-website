<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $this->cartService->add($product, $validated['quantity'] ?? 1);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product added to cart.',
                'count' => $this->cartService->count(),
            ]);
        }

        return back()->with('status', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $item)
    {
        $this->authorizeCartItem($item);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:999'],
        ]);

        $this->cartService->updateQuantity($item, $validated['quantity']);

        return back()->with('status', 'Cart updated.');
    }

    public function remove(CartItem $item)
    {
        $this->authorizeCartItem($item);

        $this->cartService->remove($item);

        return back()->with('status', 'Item removed from cart.');
    }

    protected function authorizeCartItem(CartItem $item): void
    {
        $cart = $this->cartService->getCart();
        abort_unless($item->cart_id === $cart->id, 403);
    }
}
