@extends('layouts.public')

@section('content')
    <x-public.page-hero
        :eyebrow="$page['catalogue']['eyebrow']"
        :title="$page['catalogue']['title']"
        :lead="$page['catalogue']['lead']"
    />

    <section class="py-24 section-surface-neutral text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($page['catalogue']['products'] as $index => $product)
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 reveal-fade-up flex flex-col" @if ($index > 0) style="transition-delay: {{ $index * 0.05 }}s;" @endif>
                        <span class="text-[10px] font-mono font-bold tracking-widest section-eyebrow-muted uppercase">{{ $product['type'] }}</span>
                        <h2 class="text-lg font-bold font-display text-slate-900 mt-2">{{ $product['name'] }}</h2>
                        <p class="text-sm text-slate-600 leading-relaxed mt-3 flex-1">{{ $product['note'] }}</p>
                        @if (isset($product['price']))
                            <p class="text-sm font-semibold text-forest-900 mt-4">MWK {{ number_format($product['price'], 2) }}</p>
                            @if (isset($product['id']))
                                <form action="{{ route('cart.add', $product['id']) }}" method="POST" class="mt-4">
                                    @csrf
                                    <button type="submit" class="border-forest-btn w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold">
                                        Add to Cart
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-16 flex flex-wrap justify-center gap-4">
                <a href="{{ route('quotation') }}" class="bg-forest-solid inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                    Request a Quotation &rarr;
                </a>
                <a href="{{ route('services') }}" class="border-forest-btn inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-semibold">
                    View Services
                </a>
            </div>
        </div>
    </section>
@endsection
