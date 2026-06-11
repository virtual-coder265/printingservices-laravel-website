<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ?? 'Client Account' }} | {{ $brand['short_name'] ?? config('homepage.brand.short_name') }}</title>

    @if ($hasBrandLogo)
        <link rel="icon" type="image/png" href="{{ media_url($brand['favicon'] ?? $brandLogo) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-full antialiased text-slate-700">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <aside class="auth-brand-panel relative overflow-hidden px-8 py-10 lg:w-[42%] xl:w-[38%] flex flex-col justify-between">
            <div class="relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-4 group">
                    @if ($hasBrandLogo)
                        <img
                            src="{{ media_url($brandLogo) }}"
                            alt="{{ $brand['name'] ?? 'Government Press' }}"
                            class="h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-[1.02]"
                        >
                    @else
                        <div>
                            <span class="block text-xl font-bold font-display tracking-tight text-white uppercase">{{ $brand['short_name'] ?? 'Government Press' }}</span>
                            <span class="block text-xs font-mono tracking-widest text-gold-500 uppercase mt-1">{{ $brand['tagline'] ?? '' }}</span>
                        </div>
                    @endif
                </a>
            </div>

            <div class="relative z-10 mt-10 lg:mt-0 space-y-6 max-w-md">
                <p class="text-sm font-mono tracking-widest text-gold-500 uppercase">Client Portal</p>
                <h1 class="text-3xl sm:text-4xl font-bold font-display text-white leading-tight">
                    Secure access for printing clients
                </h1>
                <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                    Manage quotations, orders, and artwork uploads through your official Department of Printing Services client account.
                </p>

                <ul class="space-y-3 text-sm text-slate-200">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 text-gold-500"><i data-lucide="shield-check" class="w-4 h-4"></i></span>
                        Verified email required before dashboard access
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 text-gold-500"><i data-lucide="lock" class="w-4 h-4"></i></span>
                        Strong passwords and login rate limiting
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 text-gold-500"><i data-lucide="file-check" class="w-4 h-4"></i></span>
                        Track quotations and production updates
                    </li>
                </ul>
            </div>

            <div class="relative z-10 mt-10 text-xs text-slate-400">
                &copy; {{ now()->year }} {{ $brand['name'] ?? config('homepage.brand.name') }}
            </div>
        </aside>

        <main class="flex-1 flex items-center justify-center px-4 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <div class="auth-form-card">
                    <div class="mb-8 lg:hidden text-center">
                        <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2">
                            @if ($hasBrandLogo)
                                <img
                                    src="{{ media_url($brandLogo) }}"
                                    alt="{{ $brand['name'] ?? 'Government Press' }}"
                                    class="h-14 w-auto object-contain"
                                >
                            @else
                                <span class="text-lg font-bold font-display text-slate-900 uppercase">{{ $brand['short_name'] ?? 'Government Press' }}</span>
                            @endif
                        </a>
                    </div>

                    {{ $slot }}

                    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-forest-900 transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Back to website
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
