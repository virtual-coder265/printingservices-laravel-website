@props(['eyebrow' => null, 'title', 'lead' => null, 'variant' => 'light', 'image' => null])

@php
    $backgroundImage = $image ?? config('homepage.page_hero.image', 'images/thumbnail.png');
@endphp

<section {{ $attributes->class(['relative py-20 bg-slate-900 overflow-hidden border-b border-white/10']) }}>
    <div class="absolute inset-0 z-0" aria-hidden="true">
        <img src="{{ media_url($backgroundImage) }}" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 hero-overlay-dark"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($eyebrow)
            <span class="text-xs font-semibold font-mono tracking-widest uppercase block text-gold-500">{{ $eyebrow }}</span>
        @endif
        <h1 class="text-3xl sm:text-4xl font-bold font-display tracking-tight mt-2 text-white">{{ $title }}</h1>
        @if ($lead)
            <p class="text-sm leading-relaxed mt-4 max-w-3xl text-slate-200">{{ $lead }}</p>
        @endif
    </div>
</section>
