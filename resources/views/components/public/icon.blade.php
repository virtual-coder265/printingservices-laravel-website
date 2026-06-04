@props(['name'])

@switch($name)
    @case('shield')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5c0 4.4-2.9 8.5-7 9.9C7.9 19.5 5 15.4 5 11V6l7-3z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12.2l1.8 1.8 3.7-4.2" />
        </svg>
        @break

    @case('publication')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 4h8l4 4v12H7z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 4v4h4M10 12h6M10 16h6" />
        </svg>
        @break

    @case('press')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 7V4h12v3M4 10h16v7a3 3 0 01-3 3H7a3 3 0 01-3-3v-7z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 14h8M8 18h5" />
        </svg>
        @break

    @case('design')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 20l6.5-6.5M14 4l6 6M11 7l6 6M4 20l4.5-1 10-10a2.1 2.1 0 00-3-3l-10 10L4 20z" />
        </svg>
        @break

    @case('finish')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10v10H7z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h10v10H4zM10 10h10v10H10z" />
        </svg>
        @break

    @case('school')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9-4 9 4-9 4-9-4z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 10.5V15c0 1.7 2.7 3 6 3s6-1.3 6-3v-4.5" />
        </svg>
        @break

    @case('brief')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 6V4h8v2M5 6h14v14H5z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 11h6M9 15h4" />
        </svg>
        @break

    @case('proof')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 4h10l4 4v12H7z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 4v4h4M10 14l2 2 4-5" />
        </svg>
        @break

    @case('delivery')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h11v8H3zM14 10h3l4 3v2h-7z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 18.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM18 18.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
        </svg>
        @break

    @case('document')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v13H7z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M10 13h6M10 17h4" />
        </svg>
        @break

    @case('phone')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 4h3l1 4-2 1.5c1 2 2.5 3.5 4.5 4.5L15.5 12l4 1v3a1.5 1.5 0 01-1.7 1.5C10.6 16.9 7.1 13.4 6.5 8.2A1.5 1.5 0 018 6.5h-.5V4z" />
        </svg>
        @break

    @case('mail')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7.5h16v9H4z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 8l7.5 6 7.5-6" />
        </svg>
        @break

    @case('location')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s6-5.2 6-11a6 6 0 10-12 0c0 5.8 6 11 6 11z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
        </svg>
        @break

    @case('clock')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
        </svg>
        @break

    @case('user')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20a7.5 7.5 0 0115 0" />
        </svg>
        @break

    @case('cart')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 5h2l1.7 8.1a1.2 1.2 0 001.2.9h8.8a1.2 1.2 0 001.2-.9L20 8H7" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.25 19.25a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5zM17 19.25a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5z" />
        </svg>
        @break

    @case('search')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <circle cx="11" cy="11" r="6.5" />
            <path stroke-linecap="round" d="M16 16l4 4" />
        </svg>
        @break

    @case('download')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v10m0 0l-4-4m4 4l4-4M5 19h14" />
        </svg>
        @break

    @case('facebook')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M13.2 21v-7.4h2.5l.4-2.9h-2.9V8.9c0-.8.2-1.4 1.4-1.4H16V4.9c-.2 0-1-.1-1.9-.1-1.9 0-3.3 1.2-3.3 3.4v2.5H8.5v2.9h2.3V21h2.4z" />
        </svg>
        @break

    @case('linkedin')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M6.3 8.6a1.7 1.7 0 110-3.3 1.7 1.7 0 010 3.3zM4.9 9.9h2.8V19H4.9V9.9zm4.7 0h2.7v1.2h.1c.4-.7 1.3-1.5 2.8-1.5 3 0 3.6 2 3.6 4.6V19H16V15c0-1 0-2.4-1.5-2.4S12.8 13.7 12.8 15V19H9.6V9.9z" />
        </svg>
        @break

    @case('x-social')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M18.9 4H21l-4.6 5.3L21.8 20h-4.5l-3.5-4.6L9.8 20H7.7l4.9-5.6L7.3 4h4.6l3.1 4.1L18.9 4zm-1.6 14.2H18L10.8 5.7H10l7.3 12.5z" />
        </svg>
        @break

    @case('instagram')
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <rect x="4.5" y="4.5" width="15" height="15" rx="4" />
            <circle cx="12" cy="12" r="3.5" />
            <circle cx="16.8" cy="7.3" r=".9" fill="currentColor" stroke="none" />
        </svg>
        @break

    @default
        <svg {{ $attributes->merge(['class' => 'h-6 w-6']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <circle cx="12" cy="12" r="8" />
        </svg>
@endswitch
