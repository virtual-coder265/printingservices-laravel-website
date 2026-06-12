@php
    $clientAreaHref = auth()->check() ? route('dashboard') : route('login');
    $clientAreaLabel = auth()->check() ? 'Dashboard' : $page['utility']['client_area_label'];
    $currentPage = $currentPage ?? '';
    $brandLogo = $page['brand']['logo'] ?? null;
    $favicon = $page['brand']['favicon'] ?? $brandLogo;
    $hasBrandLogo = media_exists($brandLogo);
    $navItems = collect($page['public_menu'] ?? [])->map(function ($item) {
        return [
            'key' => $item['key'],
            'label' => $item['label'],
            'route' => route($item['route']),
        ];
    })->all();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $page['meta']['description'] }}">
    <meta name="keywords" content="printing services, government press, malawi, secure printing, publications, binding, DPS">
    <meta name="author" content="{{ $page['brand']['name'] }}">
    <meta name="robots" content="index, follow">

    <meta property="og:title" content="{{ $page['meta']['title'] }}">
    <meta property="og:description" content="{{ $page['meta']['description'] }}">
    <meta property="og:image" content="{{ media_url($brandLogo) }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">

    <title>{{ $page['meta']['title'] }}</title>

    <link rel="icon" type="image/png" href="{{ media_url($favicon) }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body id="top" class="min-h-full flex flex-col bg-white text-slate-700 antialiased">

    <div class="top-contact-bar w-full py-2.5 hidden sm:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between font-medium">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-1.5 hover:text-white transition-colors duration-150">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-gold-500"></i>
                    <span>{{ $page['contact']['locations'][0] ?? 'Lilongwe, Malawi' }}</span>
                </div>
                <div class="flex items-center gap-1.5 hover:text-white transition-colors duration-150">
                    <i data-lucide="phone" class="w-3.5 h-3.5 text-gold-500"></i>
                    <a href="{{ $page['utility']['phone_href'] }}">{{ $page['utility']['phone'] }}</a>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-1.5 hover:text-white transition-colors duration-150">
                    <i data-lucide="mail" class="w-3.5 h-3.5 text-gold-500"></i>
                    <a href="{{ $page['utility']['email_href'] }}">{{ $page['utility']['email'] }}</a>
                </div>
                <a
                    href="{{ asset($page['utility']['charter_file']) }}"
                    download
                    class="inline-flex items-center gap-1.5 rounded border border-gold-500/50 bg-white/5 px-3 py-1 text-gold-500 hover:bg-gold-500 hover:text-black transition-all duration-300"
                >
                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                    Download {{ $page['utility']['charter_label'] }}
                </a>
                <div class="flex items-center gap-3 border-l border-white/10 pl-4">
                    @foreach ($page['utility']['social_links'] as $social)
                        <a href="{{ $social['href'] }}" class="hover:text-white text-slate-300 transition-colors duration-150" aria-label="{{ $social['label'] }}">
                            <i data-lucide="{{ $social['icon'] === 'x-social' ? 'twitter' : $social['icon'] }}" class="w-3.5 h-3.5"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <header class="sticky top-0 left-0 right-0 z-50">
        <nav class="corporate-nav w-full py-4 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                            @if ($hasBrandLogo)
                                <img
                                    class="h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                                    src="{{ media_url($brandLogo) }}"
                                    alt="{{ $page['brand']['name'] }}"
                                >
                            @else
                                <div>
                                    <span class="block text-lg font-bold font-display tracking-tight text-slate-900 uppercase">{{ $page['brand']['short_name'] }}</span>
                                    <span class="block text-[10px] font-mono tracking-widest text-slate-400 group-hover:text-slate-600 uppercase transition-colors duration-200">{{ $page['brand']['tagline'] }}</span>
                                </div>
                            @endif
                        </a>
                    </div>

                    <div class="hidden xl:block flex-1 min-w-0">
                        <div class="flex items-center justify-center gap-0.5 2xl:gap-1">
                            @foreach ($navItems as $item)
                                @php
                                    $isActive = ($currentPage === $item['key']);
                                    $activeClass = $isActive
                                        ? 'nav-link-active text-slate-900 rounded-none pb-1 font-semibold'
                                        : 'text-slate-600 hover:text-slate-900 font-medium pb-1 border-b-2 border-transparent hover:border-slate-300 rounded-none';
                                @endphp
                                <a href="{{ $item['route'] }}" class="px-2 2xl:px-3 py-2 text-sm whitespace-nowrap transition-all duration-150 {{ $activeClass }}">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="hidden xl:flex items-center gap-1 2xl:gap-2 shrink-0">
                        <a
                            href="{{ $clientAreaHref }}"
                            class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-500 hover:text-forest-900 hover:bg-slate-50 transition-colors duration-200"
                            aria-label="{{ $clientAreaLabel }}"
                            title="{{ $clientAreaLabel }}"
                        >
                            <i data-lucide="user-circle" class="w-5 h-5"></i>
                        </a>

                        <a
                            href="{{ route('cart') }}"
                            class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-500 hover:text-forest-900 hover:bg-slate-50 transition-colors duration-200"
                            aria-label="{{ $page['utility']['cart_label'] }}"
                            title="{{ $page['utility']['cart_label'] }}"
                        >
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            @if (($page['utility']['cart_count'] ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[1.125rem] h-[1.125rem] px-1 rounded-full bg-forest-900 text-[10px] font-bold text-white">
                                    {{ $page['utility']['cart_count'] }}
                                </span>
                            @endif
                        </a>

                        <a
                            href="{{ route('wishlist') }}"
                            class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-500 hover:text-forest-900 hover:bg-slate-50 transition-colors duration-200"
                            aria-label="{{ $page['utility']['wishlist_label'] }}"
                            title="{{ $page['utility']['wishlist_label'] }}"
                        >
                            <i data-lucide="heart" class="w-5 h-5"></i>
                            @if (($page['utility']['wishlist_count'] ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[1.125rem] h-[1.125rem] px-1 rounded-full bg-forest-900 text-[10px] font-bold text-white">
                                    {{ $page['utility']['wishlist_count'] }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('quotation') }}" class="border-forest-btn inline-flex items-center justify-center px-4 2xl:px-5 py-2.5 border-2 text-sm font-semibold rounded-lg hover:shadow-md transition-all duration-200 ml-1 whitespace-nowrap">
                            {{ $page['utility']['quotation_label'] }}
                        </a>
                    </div>

                    <div class="flex xl:hidden items-center gap-1 shrink-0">
                        <a
                            href="{{ route('cart') }}"
                            class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-500 hover:text-forest-900 hover:bg-slate-50 transition-colors duration-200"
                            aria-label="{{ $page['utility']['cart_label'] }}"
                            title="{{ $page['utility']['cart_label'] }}"
                        >
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            @if (($page['utility']['cart_count'] ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[1.125rem] h-[1.125rem] px-1 rounded-full bg-forest-900 text-[10px] font-bold text-white">
                                    {{ $page['utility']['cart_count'] }}
                                </span>
                            @endif
                        </a>

                        <button id="mobile-menu-btn" type="button"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-500 hover:text-forest-900 hover:bg-slate-50 transition-colors duration-200"
                                aria-controls="mobile-menu-panel" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="h-6 w-6 mobile-menu-icon-open" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg class="h-6 w-6 hidden mobile-menu-icon-close" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <div id="mobile-menu-root" class="xl:hidden hidden" aria-hidden="true">
            <button id="mobile-menu-backdrop" type="button" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-[1px]" aria-label="Close menu"></button>

            <div id="mobile-menu-panel" class="fixed inset-y-0 right-0 z-50 flex w-full max-w-sm flex-col bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4">
                    <span class="text-sm font-bold font-display uppercase tracking-wide text-slate-900">Menu</span>
                    <button id="mobile-menu-close" type="button" class="inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-500 hover:text-forest-900 hover:bg-slate-50 transition-colors" aria-label="Close menu">
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-4">
                    <nav class="space-y-1">
                        @foreach ($navItems as $item)
                            @php
                                $isActive = ($currentPage === $item['key']);
                                $mobileActiveClass = $isActive
                                    ? 'bg-slate-50 text-slate-900 border-l-4 border-forest-900 font-bold pl-3'
                                    : 'text-slate-600 hover:bg-slate-50 hover:text-forest-900 font-medium pl-4 border-l-4 border-transparent';
                            @endphp
                            <a href="{{ $item['route'] }}" class="block rounded-lg py-3 pr-3 text-base transition-colors {{ $mobileActiveClass }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <p class="px-1 mb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Account &amp; tools</p>
                        <div class="space-y-2">
                            <a href="{{ $clientAreaHref }}" class="flex items-center justify-between gap-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm font-semibold text-slate-700 hover:border-forest-800/20 hover:bg-slate-50 hover:text-forest-900 transition-colors">
                                <span class="flex items-center gap-3 min-w-0">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                                        <i data-lucide="user-circle" class="h-4 w-4"></i>
                                    </span>
                                    <span>{{ $clientAreaLabel }}</span>
                                </span>
                                <i data-lucide="chevron-right" class="h-4 w-4 shrink-0 text-slate-400"></i>
                            </a>

                            <a href="{{ route('cart') }}" class="flex items-center justify-between gap-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm font-semibold text-slate-700 hover:border-forest-800/20 hover:bg-slate-50 hover:text-forest-900 transition-colors">
                                <span class="flex items-center gap-3 min-w-0">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                                        <i data-lucide="shopping-cart" class="h-4 w-4"></i>
                                    </span>
                                    <span>
                                        {{ $page['utility']['cart_label'] }}
                                        @if (($page['utility']['cart_count'] ?? 0) > 0)
                                            <span class="block text-xs font-medium text-slate-400">{{ $page['utility']['cart_count'] }} items</span>
                                        @endif
                                    </span>
                                </span>
                                <i data-lucide="chevron-right" class="h-4 w-4 shrink-0 text-slate-400"></i>
                            </a>

                            <a href="{{ route('wishlist') }}" class="flex items-center justify-between gap-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm font-semibold text-slate-700 hover:border-forest-800/20 hover:bg-slate-50 hover:text-forest-900 transition-colors">
                                <span class="flex items-center gap-3 min-w-0">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                                        <i data-lucide="heart" class="h-4 w-4"></i>
                                    </span>
                                    <span>
                                        {{ $page['utility']['wishlist_label'] }}
                                        @if (($page['utility']['wishlist_count'] ?? 0) > 0)
                                            <span class="block text-xs font-medium text-slate-400">{{ $page['utility']['wishlist_count'] }} saved</span>
                                        @endif
                                    </span>
                                </span>
                                <i data-lucide="chevron-right" class="h-4 w-4 shrink-0 text-slate-400"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 p-4">
                    <a href="{{ route('quotation') }}" class="btn-brand btn-brand-solid block w-full px-4 py-3.5 text-center text-sm">
                        {{ $page['utility']['quotation_label'] }}
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-black border-t border-white/10 pt-16 pb-8 text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        @if ($hasBrandLogo)
                            <img class="h-10 w-auto" src="{{ media_url($brandLogo) }}" alt="{{ $page['brand']['name'] }}">
                        @else
                            <span class="text-lg font-bold font-display tracking-tight text-white uppercase">{{ $page['brand']['short_name'] }}</span>
                        @endif
                    </div>
                    <p class="text-sm leading-relaxed text-slate-400">{{ $page['footer']['summary'] }}</p>
                    <div class="flex gap-4">
                        @foreach ($page['utility']['social_links'] as $social)
                            <a href="{{ $social['href'] }}" class="hover:text-gold-500 text-slate-500 transition-colors duration-200" aria-label="{{ $social['label'] }}">
                                <i data-lucide="{{ $social['icon'] === 'x-social' ? 'twitter' : $social['icon'] }}" class="w-5 h-5"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-slate-100 uppercase tracking-widest mb-6 font-display border-l-2 border-gold-500 pl-3">Quick Links</h3>
                    <ul class="space-y-4 text-sm">
                        @foreach ($page['footer']['links'] as $link)
                            <li>
                                <a href="{{ isset($link['route']) ? route($link['route']) : ($link['href'] ?? '#') }}" class="hover:text-gold-500 transition-colors duration-150 flex items-center">
                                    <i data-lucide="chevron-right" class="w-4 h-4 mr-2 text-slate-700"></i> {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-slate-100 uppercase tracking-widest mb-6 font-display border-l-2 border-gold-500 pl-3">Physical Addresses</h3>
                    <ul class="space-y-5 text-sm">
                        @foreach ($page['footer_addresses'] ?? [] as $address)
                            <li class="flex items-start gap-3">
                                <i data-lucide="map-pin" class="w-5 h-5 text-gold-500 shrink-0 mt-0.5"></i>
                                <div class="space-y-1">
                                    <p class="text-slate-100 font-medium">{{ $address['label'] }}</p>
                                    <p>{{ $address['address'] }}</p>
                                    @if (filled($address['map_url'] ?? null))
                                        <a href="{{ $address['map_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-gold-500 hover:text-gold-400 transition-colors duration-150">
                                            View on Google Maps <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        </a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="space-y-4 text-sm">
                    <h3 class="text-sm font-semibold text-slate-100 uppercase tracking-widest mb-6 font-display border-l-2 border-gold-500 pl-3">Contact Information</h3>

                    @foreach ($page['contact']['po_boxes'] ?? [] as $poBox)
                        <div class="flex items-start gap-3">
                            <i data-lucide="mailbox" class="w-5 h-5 text-gold-500 shrink-0 mt-0.5"></i>
                            <span>{{ $poBox }}</span>
                        </div>
                    @endforeach

                    <div class="flex items-center gap-3">
                        <i data-lucide="phone" class="w-5 h-5 text-gold-500 shrink-0"></i>
                        <a href="{{ $page['contact']['phone_href'] }}" class="hover:text-gold-500 transition-colors duration-150">{{ $page['contact']['phone'] }}</a>
                    </div>

                    <div class="flex items-center gap-3">
                        <i data-lucide="mail" class="w-5 h-5 text-gold-500 shrink-0"></i>
                        <a href="{{ $page['contact']['email_href'] }}" class="hover:text-gold-500 break-all transition-colors duration-150">{{ $page['contact']['email'] }}</a>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ now()->year }} {{ $page['brand']['name'] }}. All Rights Reserved.</p>
                <div class="flex gap-6">
                    <a href="{{ asset($page['utility']['charter_file']) }}" class="hover:text-gold-500 transition-colors duration-150">Service Charter</a>
                    <a href="{{ route('home') }}#contact" class="hover:text-gold-500 transition-colors duration-150">Contact Us</a>
                    <a href="#top" class="hover:text-gold-500 transition-colors duration-150 flex items-center gap-1">
                        Back to Top <i data-lucide="arrow-up" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/{{ $page['utility']['whatsapp'] }}" target="_blank" rel="noopener noreferrer" class="whatsapp-float" aria-label="Chat on WhatsApp">
        <div class="whatsapp-pulse"></div>
        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.5-5.739-1.446L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.963C16.388 2.016 13.916.993 11.3.993c-5.437 0-9.863 4.373-9.867 9.803-.001 1.73.457 3.419 1.32 4.937L1.838 21.84l6.23-1.62c-1.5-.92-1.63-1.04-1.42-1.066zM17.487 14.39c-.3-.15-1.774-.875-2.05-.975-.276-.1-.476-.15-.676.15-.2.3-.775.975-.95 1.175-.175.2-.35.225-.65.075-.3-.15-1.267-.467-2.414-1.49-.893-.797-1.496-1.782-1.672-2.08-.175-.3-.018-.463.13-.61.135-.133.3-.35.45-.525.15-.175.2-.3.3-.5s.05-.375-.025-.525c-.075-.15-.676-1.63-.925-2.23-.24-.58-.48-.5-.66-.51-.17-.01-.37-.01-.57-.01-.2 0-.525.075-.8.375-.275.3-1.05 1.025-1.05 2.5s1.075 2.9 1.225 3.1c.15.2 2.11 3.22 5.11 4.52.714.31 1.27.496 1.703.633.715.227 1.365.195 1.88.117.574-.088 1.774-.725 2.025-1.425.25-.7.25-1.3.175-1.425-.076-.12-.276-.2-.576-.35z"/>
        </svg>
    </a>
</body>
</html>
