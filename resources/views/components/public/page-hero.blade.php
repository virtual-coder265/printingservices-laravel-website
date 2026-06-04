@props(['eyebrow' => null, 'title', 'lead' => null, 'variant' => 'light'])

<section @class([
    'py-16 border-b',
    'section-page-hero' => $variant === 'light',
    'section-page-hero section-page-hero--dark' => $variant === 'dark',
    'section-surface-gradient py-20 border-b-0' => $variant === 'gradient',
])>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($eyebrow)
            <span @class([
                'text-xs font-semibold font-mono tracking-widest uppercase block',
                'section-eyebrow' => $variant !== 'dark',
                'text-gold-500' => $variant === 'dark' || $variant === 'gradient',
            ])>{{ $eyebrow }}</span>
        @endif
        <h1 @class([
            'text-3xl sm:text-4xl font-bold font-display tracking-tight mt-2',
            'text-heading' => $variant === 'light',
            'text-heading-light' => $variant === 'dark' || $variant === 'gradient',
        ])>{{ $title }}</h1>
        @if ($lead)
            <p @class([
                'text-sm leading-relaxed mt-4 max-w-3xl',
                'text-slate-600' => $variant === 'light',
                'text-slate-300' => $variant === 'dark' || $variant === 'gradient',
            ])>{{ $lead }}</p>
        @endif
    </div>
</section>
