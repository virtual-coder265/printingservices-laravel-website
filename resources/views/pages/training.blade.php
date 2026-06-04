@extends('layouts.public')

@section('content')
    <x-public.page-hero
        :eyebrow="$page['school']['eyebrow']"
        :title="$page['school']['title']"
        :lead="$page['school']['lead']"
        variant="dark"
    />

    <section class="py-24 section-surface-white text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative reveal-fade-in">
                    <div class="quote-request-card p-4 overflow-hidden">
                        <img src="{{ asset($page['school']['image']) }}" alt="{{ $page['school']['title'] }}" class="w-full h-80 object-cover rounded-xl">
                    </div>
                </div>

                <div class="space-y-6 reveal-fade-up">
                    @foreach ($page['school']['body'] as $paragraph)
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $paragraph }}</p>
                    @endforeach

                    <ul class="space-y-3.5 text-sm font-semibold text-slate-800 pt-2">
                        @foreach ($page['school']['focus_areas'] as $focus)
                            <li class="flex items-center">
                                <span class="w-5 h-5 rounded-full check-badge flex items-center justify-center mr-3">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </span>
                                {{ $focus }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="pt-4">
                        <a href="{{ route('contact') }}" class="bg-forest-solid inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                            {{ $page['school']['cta_label'] }} &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
