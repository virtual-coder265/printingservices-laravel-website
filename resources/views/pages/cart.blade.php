@extends('layouts.public')

@section('content')
    <x-public.page-hero
        :title="$page['utility']['cart_label']"
        lead="Review selected print products before submitting your order request."
    />

    <section class="py-24 bg-white text-slate-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if ($cart->items->isEmpty())
                <div class="text-center reveal-fade-up">
                    <div class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center mx-auto mb-6 icon-accent-gold">
                        <i data-lucide="shopping-cart" class="w-7 h-7"></i>
                    </div>
                    <h2 class="text-xl font-bold font-display text-slate-900">Your cart is empty</h2>
                    <p class="text-sm text-slate-600 leading-relaxed mt-3">
                        Browse our products and add items here before checkout.
                    </p>
                    <div class="mt-8 flex flex-wrap justify-center gap-4">
                        <a href="{{ route('products') }}" class="bg-forest-solid inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                            Browse Products
                        </a>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @php
                        $subtotal = 0;
                        $vatTotal = 0;
                    @endphp

                    @foreach ($cart->items as $item)
                        @php
                            $lineSubtotal = (float) $item->product->price * $item->quantity;
                            $lineVat = round($lineSubtotal * ((float) $item->product->vat_rate / 100), 2);
                            $subtotal += $lineSubtotal;
                            $vatTotal += $lineVat;
                        @endphp
                        <div class="rounded-2xl border border-slate-100 bg-white p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold font-display text-slate-900">{{ $item->product->title }}</h3>
                                <p class="text-sm text-slate-600 mt-1">MWK {{ number_format($item->product->price, 2) }} each</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-input-corporate w-20 px-2 py-1 text-sm">
                                    <button type="submit" class="text-sm font-semibold text-forest-900">Update</button>
                                </form>
                                <form action="{{ route('cart.remove', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600">Remove</button>
                                </form>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">MWK {{ number_format($lineSubtotal + $lineVat, 2) }}</p>
                            </div>
                        </div>
                    @endforeach

                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-6">
                        <div class="flex justify-between text-sm mb-2"><span>Subtotal</span><span>MWK {{ number_format($subtotal, 2) }}</span></div>
                        <div class="flex justify-between text-sm mb-2"><span>VAT (17.5%)</span><span>MWK {{ number_format($vatTotal, 2) }}</span></div>
                        <div class="flex justify-between font-bold text-lg border-t border-slate-200 pt-3 mt-3"><span>Total</span><span>MWK {{ number_format($subtotal + $vatTotal, 2) }}</span></div>
                    </div>

                    @auth
                        <form action="{{ route('checkout.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <textarea name="notes" rows="3" placeholder="Order notes (optional)" class="form-input-corporate w-full px-3.5 py-2.5 text-sm">{{ old('notes') }}</textarea>
                            <button type="submit" class="bg-forest-solid w-full inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                                Submit Order Request &rarr;
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-forest-solid w-full inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                            Log in to checkout &rarr;
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </section>
@endsection
