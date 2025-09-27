<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    
    @if(isset($site))
        <!-- Gym-Specific Favicon -->
        @if($site->getFirstMediaUrl('favicon'))
            <link rel="icon" type="image/x-icon" href="{{ $site->getFirstMediaUrl('favicon') }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ $site->getFirstMediaUrl('favicon') }}">
            <link rel="apple-touch-icon" href="{{ $site->getFirstMediaUrl('favicon') }}">
        @else
            <link rel="icon" type="image/x-icon" href="{{ asset('assets/media/logos/favicon.ico') }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/media/logos/favicon.ico') }}">
        @endif
        
        <!-- Gym-Specific Meta Tags -->
        <title>@yield('title', 'Admin Dashboard - ' . $site->gym_name)</title>
        <meta name="description" content="@yield('meta_description', 'Admin dashboard for ' . $site->gym_name . ' - Manage members, classes, and gym operations.')">
        <meta name="keywords" content="@yield('meta_keywords', 'admin, dashboard, gym management, ' . $site->gym_name . ', fitness management')">
        <meta name="author" content="{{ $site->gym_name }}">
        
        <!-- Prevent Search Engine Indexing -->
        <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex, notranslate">
        <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="slurp" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="duckduckbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="baiduspider" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="yandexbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        
        <!-- Additional Security Meta Tags -->
        <meta name="referrer" content="no-referrer">
        <meta name="format-detection" content="telephone=no">
        <meta http-equiv="X-Robots-Tag" content="noindex, nofollow, noarchive, nosnippet, noimageindex, notranslate">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="@yield('og_title', 'Admin Dashboard - ' . $site->gym_name)">
        <meta property="og:description" content="@yield('og_description', 'Admin dashboard for ' . $site->gym_name)">
        <meta property="og:image" content="@yield('og_image', $site->getFirstMediaUrl('gym_logo') ?? asset('assets/media/logos/logo.png'))">
        <meta property="og:site_name" content="{{ $site->gym_name }}">
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="@yield('twitter_title', 'Admin Dashboard - ' . $site->gym_name)">
        <meta name="twitter:description" content="@yield('twitter_description', 'Admin dashboard for ' . $site->gym_name)">
        <meta name="twitter:image" content="@yield('twitter_image', $site->getFirstMediaUrl('gym_logo') ?? asset('assets/media/logos/logo.png'))">
        
        <!-- Additional Admin Meta Tags -->
        <meta name="theme-color" content="#009ef7">
        <meta name="msapplication-TileColor" content="#009ef7">
        <meta name="apple-mobile-web-app-title" content="{{ $site->gym_name }} Admin">
        <meta name="application-name" content="{{ $site->gym_name }} Admin">
    @else
        <!-- Default Admin Meta Tags when no gym context -->
        <title>@yield('title', 'Gymivida Admin Dashboard')</title>
        <meta name="description" content="@yield('meta_description', 'Gymivida Admin Dashboard - Gym management platform administration.')">
        <meta name="keywords" content="@yield('meta_keywords', 'Gymivida, admin, dashboard, gym management')">
        <meta name="author" content="Gymivida">
        
        <!-- Prevent Search Engine Indexing -->
        <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex, notranslate">
        <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="slurp" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="duckduckbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="baiduspider" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        <meta name="yandexbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
        
        <!-- Additional Security Meta Tags -->
        <meta name="referrer" content="no-referrer">
        <meta name="format-detection" content="telephone=no">
        <meta http-equiv="X-Robots-Tag" content="noindex, nofollow, noarchive, nosnippet, noimageindex, notranslate">
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/media/logos/favicon.ico') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/media/logos/favicon.ico') }}">
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="article" />
    @endif

    {!! getStyles() !!}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/css/intlTelInput.css">

    {{-- Inject gym branding CSS variables --}}
    <x-gym-branding-css />

</head>

@yield('css')