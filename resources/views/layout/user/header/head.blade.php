<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @if(isset($siteSetting))
        <!-- Gym-Specific Favicon -->
        @if($siteSetting->getFirstMediaUrl('favicon'))
            <link rel="icon" type="image/x-icon" href="{{ $siteSetting->getFirstMediaUrl('favicon') }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ $siteSetting->getFirstMediaUrl('favicon') }}">
            <link rel="apple-touch-icon" href="{{ $siteSetting->getFirstMediaUrl('favicon') }}">
        @else
            <link rel="icon" type="image/x-icon" href="{{ asset('assets/master-assets/img/favicon/favicon.ico') }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/master-assets/img/favicon/favicon.ico') }}">
        @endif
        
        <!-- Gym-Specific Meta Tags -->
        <title>@yield('title', $siteSetting->gym_name . ' - Premium Fitness & Wellness')</title>
        <meta name="description" content="@yield('meta_description', $siteSetting->description ?? 'Join ' . $siteSetting->gym_name . ' for premium fitness training, state-of-the-art equipment, and expert guidance. Transform your fitness journey with us.')">
        <meta name="keywords" content="@yield('meta_keywords', 'gym, fitness, workout, training, ' . $siteSetting->gym_name . ', exercise, health, wellness')">
        <meta name="author" content="{{ $siteSetting->gym_name }}">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ request()->url() }}">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ request()->url() }}">
        <meta property="og:title" content="@yield('og_title', $siteSetting->gym_name . ' - Premium Fitness & Wellness')">
        <meta property="og:description" content="@yield('og_description', $siteSetting->description ?? 'Join ' . $siteSetting->gym_name . ' for premium fitness training and expert guidance.')">
        <meta property="og:image" content="@yield('og_image', $siteSetting->getFirstMediaUrl('gym_logo') ?? asset('assets/user/img/logo.png'))">
        <meta property="og:site_name" content="{{ $siteSetting->gym_name }}">
        <meta property="og:locale" content="en_US">
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ request()->url() }}">
        <meta name="twitter:title" content="@yield('twitter_title', $siteSetting->gym_name . ' - Premium Fitness & Wellness')">
        <meta name="twitter:description" content="@yield('twitter_description', $siteSetting->description ?? 'Join ' . $siteSetting->gym_name . ' for premium fitness training and expert guidance.')">
        <meta name="twitter:image" content="@yield('twitter_image', $siteSetting->getFirstMediaUrl('gym_logo') ?? asset('assets/user/img/logo.png'))">
        @if($siteSetting->x_url)
            <meta name="twitter:site" content="{{ $siteSetting->x_url }}">
        @endif
        
        <!-- Additional SEO Meta Tags -->
        <meta name="theme-color" content="#ff6b35">
        <meta name="msapplication-TileColor" content="#ff6b35">
        <meta name="apple-mobile-web-app-title" content="{{ $siteSetting->gym_name }}">
        <meta name="application-name" content="{{ $siteSetting->gym_name }}">
        
        <!-- Structured Data for Local Business -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            "name": "{{ $siteSetting->gym_name }}",
            "description": "{{ $siteSetting->description ?? 'Premium fitness and wellness center' }}",
            @if($siteSetting->contact_email)
            "email": "{{ $siteSetting->contact_email }}",
            @endif
            @if($siteSetting->site_url)
            "url": "{{ $siteSetting->site_url }}",
            @endif
            @if($siteSetting->getFirstMediaUrl('gym_logo'))
            "logo": "{{ $siteSetting->getFirstMediaUrl('gym_logo') }}",
            @endif
            @if($siteSetting->address)
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "{{ $siteSetting->address['city'] ?? 'City' }}",
                "addressRegion": "{{ $siteSetting->address['state'] ?? 'State' }}",
                "addressCountry": "{{ $siteSetting->address['country'] ?? 'Country' }}"
            },
            @endif
            "priceRange": "$$",
            "telephone": "{{ $siteSetting->phone ?? '' }}"
        }
        </script>
    @else
        <!-- Default Meta Tags when no gym context -->
        <title>@yield('title', 'Gymify - Multi-Tenant Gym Management Platform')</title>
        <meta name="description" content="@yield('meta_description', 'Gymify is a comprehensive multi-tenant gym management platform. Connect with your local gym for premium fitness training and wellness services.')">
        <meta name="keywords" content="@yield('meta_keywords', 'gymify, gym management, fitness platform, multi-tenant, gym software')">
        <meta name="author" content="Gymify">
        <meta name="robots" content="index, follow">
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/user/img/favicon.ico') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/user/img/favicon.ico') }}">
    @endif

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500,600,700&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('assets/user/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/user/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/user/css/flaticon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/user/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/user/css/barfiller.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/user/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/user/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/toastr.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/user/css/style.css') }}" type="text/css">
</head>

@yield('css')

    
