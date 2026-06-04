@extends('layouts.public')

@section('content')
    <x-public.page-hero
        eyebrow="Our Services"
        :title="'Professional printing services for public and private clients'"
        :lead="'End-to-end services from design preparation and secure printing to finishing, binding, and distribution.'"
        variant="light"
    />

    <section class="py-24 section-surface-white text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($page['services'] as $index => $service)
                    @php
                        $lucideIcon = $page['service_icons'][$service['icon']] ?? 'printer';
                    @endphp
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm reveal-fade-up" @if ($index > 0) style="transition-delay: {{ $index * 0.05 }}s;" @endif>
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center icon-accent-gold mb-4">
                            <i data-lucide="{{ $lucideIcon }}" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-mono font-bold tracking-widest section-eyebrow-muted uppercase">{{ $service['category'] }}</span>
                        <h2 class="text-lg font-bold font-display text-slate-900 mt-2">{{ $service['title'] }}</h2>
                        <p class="text-sm text-slate-600 leading-relaxed mt-3">{{ $service['description'] }}</p>
                        <ul class="mt-4 space-y-2">
                            @foreach ($service['points'] as $point)
                                <li class="flex items-start gap-2 text-xs text-slate-700">
                                    <i data-lucide="check" class="w-3.5 h-3.5 icon-accent shrink-0 mt-0.5"></i>
                                    <span>{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 text-center">
                <a href="{{ route('quotation') }}" class="bg-forest-solid inline-flex items-center justify-center px-6 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                    Request a Quotation &rarr;
                </a>
            </div>
        </div>
    </section>
@endsection
