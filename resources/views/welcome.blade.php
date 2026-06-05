@extends('layouts.public')

@php
    $heroSlide = $page['hero_slides'][0] ?? null;
    $featuredServices = collect($page['services'])->take(4)->values();
    $serviceImages = [
        $page['overview']['image_primary'],
        $page['catalogue']['image_primary'],
        $page['catalogue']['image_secondary'],
        $page['school']['image'],
    ];
@endphp

@section('content')
    <section id="hero" data-hero-slider-section class="relative min-h-[85vh] flex items-center justify-center bg-slate-900 overflow-hidden py-16">
        <div class="absolute inset-0 z-0 hero-slider" data-hero-slider data-hero-slider-interval="6500" aria-hidden="true">
            @foreach ($page['hero_slides'] as $index => $slide)
                <div class="hero-slide{{ $index === 0 ? ' is-active' : '' }}" data-hero-slide>
                    <img
                        src="{{ media_url($slide['image']) }}"
                        alt=""
                        class="hero-slide-image"
                        @if ($index === 0) fetchpriority="high" @endif
                    >
                </div>
            @endforeach
            <div class="absolute inset-0 hero-overlay-dark"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-12">
            <div class="max-w-3xl text-left space-y-6">
                <span class="text-sm font-medium font-mono tracking-widest text-gold-500 uppercase block">
                    {{ $heroSlide['eyebrow'] ?? $page['brand']['tagline'] }}
                </span>

                <h1 class="text-4xl sm:text-6xl font-bold font-display tracking-tight text-white leading-[1.1] reveal-fade-up">
                    {{ $heroSlide['title'] ?? 'Secure state printing with national reach' }}<br>
                    <span class="text-gold font-semibold">{{ $page['brand']['tagline'] }}</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-100 max-w-xl font-normal leading-relaxed reveal-fade-up" style="transition-delay: 0.08s;">
                    {{ $heroSlide['description'] ?? $page['meta']['description'] }}
                </p>

                <div class="pt-6 flex flex-wrap gap-4 reveal-fade-up" style="transition-delay: 0.16s;">
                    <a href="{{ route('services') }}" class="btn-brand btn-brand-solid px-6 py-3.5 text-sm shadow-lg">
                        Our Services
                    </a>
                    <a href="{{ route('products') }}" class="btn-brand btn-brand-light px-6 py-3.5 text-sm">
                        View Catalogue
                    </a>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-0 right-0 z-10 flex justify-center gap-2" data-hero-slider-dots>
            @foreach ($page['hero_slides'] as $index => $slide)
                <button
                    type="button"
                    class="slider-dot{{ $index === 0 ? ' active' : '' }}"
                    data-hero-slide-dot="{{ $index }}"
                    aria-label="Show hero image {{ $index + 1 }}"
                    aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                ></button>
            @endforeach
        </div>
    </section>

    <section class="relative z-20 -mt-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="floating-metrics-bar grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 p-8 gap-y-6">
            @foreach ($page['highlights'] as $index => $highlight)
                <div class="flex items-start gap-4 px-2 {{ $index < 3 ? 'sm:metric-divider' : '' }} {{ $index === 1 ? 'sm:pl-6' : '' }} {{ $index === 2 ? 'lg:pl-6' : '' }}">
                    <div class="icon-accent-gold mt-1">
                        <i data-lucide="{{ $highlight['icon'] }}" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold font-display text-slate-900 uppercase tracking-wide">{{ $highlight['title'] }}</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $highlight['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="overview" class="py-24 bg-white text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6 reveal-fade-up">
                        <span class="text-xs font-semibold font-mono tracking-widest section-eyebrow uppercase block">{{ $page['overview']['eyebrow'] }}</span>
                        <h2 class="text-3xl font-black font-display text-slate-900 tracking-tight leading-tight">
                            {{ $page['overview']['title'] }}
                        </h2>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            {{ $page['overview']['lead'] }}
                        </p>

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

                        <div class="pt-4">
                            <a href="{{ $page['overview']['cta_href'] }}" class="bg-forest-solid inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                                {{ $page['overview']['cta_label'] }} &rarr;
                            </a>
                        </div>
                    </div>

                    <div class="welcome-image-stack flex flex-col gap-6 justify-center relative reveal-fade-in" style="transition-delay: 0.1s;">
                        <div class="relative w-11/12 rounded-2xl overflow-hidden shadow-lg border border-slate-100">
                            <img src="{{ media_url($page['overview']['image_primary']) }}" alt="Department of Printing Services production" class="w-full h-48 object-cover">
                        </div>
                        <div class="relative w-11/12 self-end rounded-2xl overflow-hidden shadow-lg border border-slate-100 -mt-8 z-10">
                            <img src="{{ media_url($page['overview']['image_secondary']) }}" alt="Government Press printed output" class="w-full h-48 object-cover">
                        </div>

                        <div class="welcome-badge-5 absolute top-1/2 left-4 -translate-y-12 z-20 p-4 w-28 h-28 flex flex-col items-center justify-center text-center">
                            <span class="block text-2xl font-extrabold text-gold-500 font-display">{{ $page['stats'][0]['value'] }}</span>
                            <span class="block text-[8px] font-mono font-bold tracking-widest text-white uppercase mt-0.5">{{ $page['stats'][0]['label'] }}</span>
                            <span class="block text-[8px] font-mono font-bold tracking-widest text-white uppercase">Heritage</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 reveal-fade-in" style="transition-delay: 0.15s;">
                    <div class="quote-request-card">
                        <div class="quote-card-header px-6 py-5 text-center">
                            <h3 class="text-lg font-bold text-white font-display tracking-tight">Request a Quotation</h3>
                            <p class="text-xs text-slate-300 mt-1">Tell us about your print job and we will get back to you.</p>
                        </div>

                        <form id="secure-contact-form" action="#" method="POST" class="p-6 space-y-4" data-mailto="{{ $page['contact']['email_href'] }}">
                            @csrf

                            <div>
                                <input type="text" id="name" name="name" placeholder="Full Name *" required class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                                <span class="form-error-msg text-[10px] text-red-500 mt-1 block"></span>
                            </div>

                            <div>
                                <input type="text" id="phone" name="phone" placeholder="Phone Number" class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                                <span class="form-error-msg text-[10px] text-red-500 mt-1 block"></span>
                            </div>

                            <div>
                                <input type="email" id="email" name="email" placeholder="Email Address *" required class="form-input-corporate w-full px-3.5 py-2.5 text-xs">
                                <span class="form-error-msg text-[10px] text-red-500 mt-1 block"></span>
                            </div>

                            <div>
                                <select id="service" name="service" required class="form-input-corporate w-full px-3.5 py-2.5 text-xs bg-[#f8fafc]">
                                    <option value="" disabled selected>Service Required *</option>
                                    @foreach ($page['services'] as $service)
                                        <option value="{{ Str::slug($service['title']) }}">{{ $service['title'] }}</option>
                                    @endforeach
                                </select>
                                <span class="form-error-msg text-[10px] text-red-500 mt-1 block"></span>
                            </div>

                            <div>
                                <textarea id="message" name="message" rows="3" required placeholder="Tell us about your print job *" class="form-input-corporate w-full px-3.5 py-2.5 text-xs"></textarea>
                                <span class="form-error-msg text-[10px] text-red-500 mt-1 block"></span>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="btn-brand btn-brand-solid w-full px-4 py-3 text-xs tracking-wider uppercase">
                                    Submit Request &nbsp; &rarr;
                                </button>
                            </div>

                            <p class="text-[10px] text-slate-400 text-center flex items-center justify-center gap-1.5 pt-1.5 border-t border-slate-100">
                                <i data-lucide="lock" class="w-3 h-3 text-slate-400"></i> We respect your privacy.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 section-surface-neutral border-y border-slate-100 text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal-fade-up">
                <span class="text-xs font-semibold font-mono tracking-widest section-eyebrow uppercase block">Why Choose Us</span>
                <h2 class="text-3xl sm:text-4xl font-black font-display text-slate-900 tracking-tight mt-2">We Deliver Value at Every Step</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-8">
                @foreach ($page['values'] as $index => $value)
                    <div class="flex flex-col items-center text-center p-2 reveal-fade-up" @if ($index > 0) style="transition-delay: {{ $index * 0.05 }}s;" @endif>
                        <div class="icon-accent-gold mb-4 bg-white p-3 rounded-full shadow-sm border border-slate-100">
                            <i data-lucide="{{ $value['icon'] }}" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-sm font-bold font-display text-slate-900 uppercase tracking-wide">{{ $value['title'] }}</h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $value['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="services" class="py-24 bg-white text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 reveal-fade-up">
                <span class="text-xs font-semibold font-mono tracking-widest section-eyebrow uppercase block">Our Services</span>
                <h2 class="text-3xl sm:text-4xl font-black font-display text-slate-900 tracking-tight mt-2 inline-block relative pb-4">
                    Our Services
                    <span class="absolute bottom-0 left-1/4 right-1/4 h-1 bg-gold-500 rounded"></span>
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($featuredServices as $index => $service)
                    @php
                        $lucideIcon = $page['service_icons'][$service['icon']] ?? 'printer';
                        $image = $serviceImages[$index] ?? $page['overview']['image_primary'];
                    @endphp
                    <div class="service-card-dark rounded-2xl overflow-hidden flex flex-col justify-between reveal-fade-up" @if ($index > 0) style="transition-delay: {{ $index * 0.05 }}s;" @endif>
                        <div>
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ media_url($image) }}" alt="{{ $service['title'] }}" class="w-full h-full object-cover">
                                <div class="service-icon-holder absolute -bottom-6 left-6 w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition-colors duration-300">
                                    <i data-lucide="{{ $lucideIcon }}" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <div class="p-6 pt-8 space-y-3">
                                <h3 class="text-lg font-bold text-white font-display">{{ $service['title'] }}</h3>
                                <p class="text-xs text-slate-400 leading-relaxed">{{ $service['description'] }}</p>
                            </div>
                        </div>
                        <div class="p-6 pt-0">
                            <a href="{{ route('home') }}#services" class="inline-flex items-center text-xs font-bold text-white hover:text-gold-500 transition-colors duration-150">
                                Learn More &nbsp;&rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="catalogue" class="py-24 section-surface-dark relative overflow-hidden border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-4 space-y-6 reveal-fade-up">
                    <span class="text-xs font-semibold font-mono tracking-widest text-gold-500 uppercase block">{{ $page['catalogue']['eyebrow'] }}</span>
                    <h2 class="text-3xl sm:text-4xl font-black font-display text-white tracking-tight leading-tight">
                        {{ $page['catalogue']['title'] }}
                    </h2>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        {{ $page['catalogue']['lead'] }}
                    </p>
                    <div class="pt-4">
                        <a href="{{ route('quotation') }}" class="btn-brand btn-brand-light-alt inline-flex px-5 py-3 text-xs">
                            Request Quotation &nbsp;&rarr;
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-8 overflow-x-auto pb-4 flex gap-6 reveal-fade-in" style="transition-delay: 0.1s;">
                    @foreach ($page['featured_images'] as $featured)
                        <div class="min-w-[280px] sm:min-w-[320px] bg-black/40 border border-white/10 rounded-2xl overflow-hidden shadow-xl shrink-0 group backdrop-blur-sm">
                            <div class="h-48 overflow-hidden relative">
                                <img src="{{ media_url($featured['image']) }}" alt="{{ $featured['alt'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="charter" class="py-16 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($page['trust_metrics'] as $index => $metric)
                    <div class="flex items-center gap-4 justify-center reveal-fade-up" @if ($index > 0) style="transition-delay: {{ $index * 0.05 }}s;" @endif>
                        <i data-lucide="{{ $metric['icon'] }}" class="w-6 h-6 text-gold-500 shrink-0"></i>
                        <div class="text-left">
                            <h4 class="text-xs font-bold text-slate-900 uppercase font-display tracking-wider">{{ $metric['title'] }}</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $metric['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="school" class="py-24 section-surface-gradient-minimal border-y border-slate-100 text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="relative reveal-fade-in">
                    <div class="quote-request-card p-4 overflow-hidden">
                        <img src="{{ media_url($page['school']['image']) }}" alt="{{ $page['school']['title'] }}" class="w-full h-80 object-cover rounded-xl">
                    </div>
                </div>

                <div class="space-y-6 reveal-fade-up">
                    <span class="text-xs font-semibold font-mono tracking-widest section-eyebrow uppercase block">{{ $page['school']['eyebrow'] }}</span>
                    <h2 class="text-3xl font-black font-display text-slate-900 tracking-tight leading-tight">{{ $page['school']['title'] }}</h2>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $page['school']['lead'] }}</p>

                    @foreach ($page['school']['body'] as $paragraph)
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $paragraph }}</p>
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
                        <a href="{{ $page['school']['cta_href'] }}" class="bg-forest-solid inline-flex items-center justify-center px-5 py-3 rounded-lg text-sm font-bold text-white shadow-md">
                            {{ $page['school']['cta_label'] }} &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="py-24 bg-white text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
                <div class="space-y-6 reveal-fade-up">
                    <span class="text-xs font-semibold font-mono tracking-widest section-eyebrow uppercase block">{{ $page['contact']['eyebrow'] }}</span>
                    <h2 class="text-3xl font-black font-display text-slate-900 tracking-tight leading-tight">{{ $page['contact']['title'] }}</h2>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $page['contact']['lead'] }}</p>

                    <div class="space-y-4 pt-4">
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
                </div>

                <div class="quote-request-card reveal-fade-in" style="transition-delay: 0.1s;">
                    <div class="quote-card-header px-6 py-5">
                        <h3 class="text-lg font-bold text-white font-display tracking-tight">Quotation Checklist</h3>
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

                        <div class="pt-4 flex flex-wrap gap-3">
                            <a href="{{ asset($page['utility']['charter_file']) }}" class="border-forest-btn inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200">
                                Download Charter
                            </a>
                            <a href="{{ $page['contact']['email_href'] }}" class="bg-forest-solid inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-bold">
                                Email Front Office
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
