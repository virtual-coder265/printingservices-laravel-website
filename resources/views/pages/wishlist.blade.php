@extends('layouts.public')

@section('content')
    <x-public.page-hero
        :title="$page['utility']['wishlist_label']"
        lead="Save products and services you want to revisit or include in a future quotation."
    />

    <section class="py-24 bg-white text-slate-700">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal-fade-up">
            <div class="w-16 h-16 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center mx-auto mb-6 icon-accent-gold">
                <i data-lucide="heart" class="w-7 h-7"></i>
            </div>
            <h2 class="text-xl font-bold font-display text-slate-900">Your wishlist is empty</h2>
            <p class="text-sm text-slate-600 leading-relaxed mt-3">
                Save items from the catalogue to compare options or prepare a quotation request later.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('products') }}" class="bg-forest-solid inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                    Browse Products
                </a>
                <a href="{{ route('services') }}" class="border-forest-btn inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-semibold">
                    View Services
                </a>
            </div>
        </div>
    </section>
@endsection
