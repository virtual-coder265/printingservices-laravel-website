@extends('layouts.public')

@section('content')
    <x-public.page-hero
        :eyebrow="$page['overview']['eyebrow']"
        :title="$page['overview']['title']"
        :lead="$page['overview']['lead']"
        variant="dark"
    />

    <section class="py-24 bg-white text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <div class="lg:col-span-7 space-y-6 reveal-fade-up">
                    @foreach ($page['overview']['body'] as $paragraph)
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $paragraph }}</p>
                    @endforeach

                    <div class="rounded-2xl section-surface-neutral border border-slate-100 p-6">
                        <h2 class="text-sm font-bold font-display text-slate-900 uppercase tracking-wide">Our Mission</h2>
                        <p class="text-sm text-slate-600 leading-relaxed mt-3">{{ $page['overview']['mission'] }}</p>
                    </div>

                    <ul class="space-y-3.5 text-sm font-semibold text-slate-800">
                        @foreach ($page['overview']['points'] as $point)
                            <li class="flex items-center">
                                <span class="w-5 h-5 rounded-full check-badge flex items-center justify-center mr-3">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </span>
                                {{ $point }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 gap-4 reveal-fade-in">
                    @foreach ($page['overview']['cards'] as $card)
                        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                            <h3 class="text-sm font-bold font-display text-slate-900 uppercase tracking-wide">{{ $card['title'] }}</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $card['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-16 flex flex-wrap gap-4">
                <a href="{{ route('services') }}" class="bg-forest-solid inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                    Explore Services &rarr;
                </a>
                <a href="{{ route('contact') }}" class="border-forest-btn inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-semibold">
                    Contact Us
                </a>
            </div>
        </div>
    </section>
@endsection
