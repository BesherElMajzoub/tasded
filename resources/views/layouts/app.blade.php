<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0E3E35">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <title>@yield('title', 'أصل القمة | خدمات سداد القروض')</title>
    <meta name="description" content="@yield('description', 'خدمات استشارية وتنسيقية لسداد القروض القائمة وطلب تمويل جديد في الرياض وجدة.')">
    <link rel="canonical" href="@yield('canonical', route('landing.sadad'))">
    <meta property="og:locale" content="ar_SA">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og_title', 'أصل القمة | خيارات سداد القروض')">
    <meta property="og:description" content="@yield('description', 'خدمات استشارية وتنسيقية واضحة في الرياض وجدة.')">
    <meta property="og:url" content="@yield('canonical', route('landing.sadad'))">
    <meta property="og:image" content="{{ asset('images/riyadh-hero.webp') }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php($analytics = config('landing.analytics'))
    @if ($analytics['gtm_id'] || $analytics['ga4_id'] || $analytics['google_ads_id'])
        <link rel="preconnect" href="https://www.googletagmanager.com">
    @endif
    @if ($analytics['gtm_id'])
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
            var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
            j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer',@js($analytics['gtm_id']));
        </script>
    @elseif ($analytics['ga4_id'] || $analytics['google_ads_id'])
        @php($googleTagId = $analytics['ga4_id'] ?: $analytics['google_ads_id'])
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleTagId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            @if ($analytics['ga4_id']) gtag('config', @js($analytics['ga4_id'])); @endif
            @if ($analytics['google_ads_id']) gtag('config', @js($analytics['google_ads_id'])); @endif
        </script>
    @endif
    @stack('head')
</head>
<body
    data-analytics='@json($analytics)'
    data-click-endpoint="{{ route('contact-clicks.store') }}"
    data-csrf="{{ csrf_token() }}"
    data-variant="{{ $variant ?? '' }}"
>
    @if ($analytics['gtm_id'])
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $analytics['gtm_id'] }}" height="0" width="0" class="tracking-frame" title="Google Tag Manager"></iframe></noscript>
    @endif

    <a href="#main-content" class="skip-link">انتقل إلى المحتوى الرئيسي</a>
    @yield('body')
</body>
</html>
