<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function store(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('status', 'Please log in to complete your order.');
        }

        $cart = $this->cartService->getCart()->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $profile = Auth::user()->customerProfile ?? CustomerProfile::create([
            'user_id' => Auth::id(),
            'type' => 'individual',
        ]);

        $order = DB::transaction(function () use ($cart, $profile, $validated) {
            $order = Order::create([
                'reference' => Order::generateReference(),
                'customer_profile_id' => $profile->id,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => $validated['notes'] ?? null,
                'vat_rate' => 17.50,
            ]);

            foreach ($cart->items as $item) {
                $lineSubtotal = (float) $item->product->price * $item->quantity;
                $vatAmount = round($lineSubtotal * 0.175, 2);
                $lineTotal = $lineSubtotal + $vatAmount;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'description' => $item->product->title,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price,
                    'vat_rate' => $item->product->vat_rate,
                    'vat_amount' => $vatAmount,
                    'line_total' => $lineTotal,
                ]);
            }

            $subtotal = $order->items()->sum(DB::raw('unit_price * quantity'));
            $vatAmount = $order->items()->sum('vat_amount');
            $order->update([
                'subtotal' => $subtotal,
                'vat_amount' => $vatAmount,
                'total_amount' => $subtotal + $vatAmount,
            ]);

            $this->cartService->clear();

            return $order;
        });

        return redirect()->route('portal.orders.show', $order)
            ->with('status', 'Your order has been submitted.');
    }
}
