@extends('layouts.public')

@section('content')
    <x-public.page-hero
        :eyebrow="$page['contact']['eyebrow']"
        :title="$page['contact']['title']"
        :lead="$page['contact']['lead']"
    />

    <section class="py-24 bg-white text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                <div class="space-y-6 reveal-fade-up">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-5 h-5 text-gold-500 shrink-0"></i>
                            <a href="{{ $page['contact']['phone_href'] }}" class="hover:text-forest-900 transition-colors">{{ $page['contact']['phone'] }}</a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-5 h-5 text-gold-500 shrink-0"></i>
                            <a href="{{ $page['contact']['email_href'] }}" class="hover:text-forest-900 break-all transition-colors">{{ $page['contact']['email'] }}</a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i data-lucide="clock" class="w-5 h-5 text-gold-500 shrink-0"></i>
                            <span>{{ $page['contact']['hours'] }}</span>
                        </div>
                        @foreach ($page['contact']['locations'] as $location)
                            <div class="flex items-start gap-3">
                                <i data-lucide="map-pin" class="w-5 h-5 text-gold-500 shrink-0 mt-0.5"></i>
                                <span>{{ $location }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4">
                        <a href="{{ route('quotation') }}" class="bg-forest-solid inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                            Request a Quotation &rarr;
                        </a>
                    </div>
                </div>

                <div class="quote-request-card reveal-fade-in">
                    <div class="quote-card-header px-6 py-5">
                        <h2 class="text-lg font-bold text-white font-display tracking-tight">Quotation Checklist</h2>
                        <p class="text-xs text-slate-300 mt-1">Include these details when requesting a print job.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach ($page['contact']['quote_checklist'] as $item)
                            <div class="flex items-start gap-3 text-sm text-slate-700">
                                <span class="w-5 h-5 rounded-full check-badge flex items-center justify-center shrink-0 mt-0.5">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </span>
                                <span>{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
