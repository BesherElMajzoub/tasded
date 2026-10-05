@props(['name'])

<svg {{ $attributes->merge(['class' => 'icon', 'viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.8', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('phone')
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.69 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.33 1.85.56 2.81.69A2 2 0 0 1 22 16.92Z"/>
            @break
        @case('whatsapp')
            <path d="M20.5 11.7a8.5 8.5 0 0 1-12.6 7.45L3 20.5l1.3-4.72A8.5 8.5 0 1 1 20.5 11.7Z"/>
            <path d="M8.2 7.6c.2-.45.42-.46.72-.47h.62c.2 0 .43.08.54.4l.76 1.84c.08.22.04.4-.1.58l-.58.7c-.16.18-.12.34-.04.5.52.92 1.25 1.7 2.14 2.28.18.1.35.13.5-.04l.76-.9c.16-.18.34-.22.58-.13l1.8.85c.27.13.45.2.5.34.06.14.06.8-.18 1.4-.25.58-1.42 1.12-1.96 1.17-.5.05-1.16.08-1.88-.15-.43-.13-.98-.32-1.68-.62a9.8 9.8 0 0 1-3.62-3.2c-.75-1.02-1.28-2.15-1.3-3.06 0-.9.48-1.36.66-1.55.18-.2.4-.25.54-.25"/>
            @break
        @case('balance')
            <path d="M12 3v18M5 6h14M5 6l-3 7h6L5 6ZM19 6l-3 7h6l-3-7ZM8 21h8"/>
            @break
        @case('document')
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8M8 17h5"/>
            @break
        @case('location')
            <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/>
            @break
        @case('check')
            <path d="m20 6-11 11-5-5"/>
            @break
        @case('shield')
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>
            @break
        @case('info')
            <circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/>
            @break
        @case('menu')
            <path d="M4 7h16M4 12h16M4 17h16"/>
            @break
        @case('percentage')
            <path d="m19 5-14 14"/><circle cx="7" cy="7" r="2"/><circle cx="17" cy="17" r="2"/>
            @break
        @case('calendar')
            <rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>
            @break
        @case('calculator')
            <rect x="4" y="2" width="16" height="20" rx="2"/><path d="M8 6h8M8 11h.01M12 11h.01M16 11h.01M8 15h.01M12 15h.01M16 15h.01M8 19h.01M12 19h4"/>
            @break
        @default
            <circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
