<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-X1VS4J5MM1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-X1VS4J5MM1');
    </script>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Gymivida Master Branding Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/master-assets/img/favicon/favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/master-assets/img/favicon/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/master-assets/img/favicon/favicon.ico') }}">
    
    <!-- Gymivida Master Meta Tags -->
    <title>@yield('title', 'Gymivida - Gym Management Platform')</title>
    <meta name="description" content="@yield('meta_description', 'Gymivida is a comprehensive gym management platform. Choose from our network of premium fitness facilities and find the perfect gym for your fitness journey with state-of-the-art equipment and expert trainers.')">
    <meta name="keywords" content="@yield('meta_keywords', 'Gymivida, gym management, fitness platform, gym software, fitness facilities, workout, exercise, health, wellness, training')">
    <meta name="author" content="Gymivida">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ request()->url() }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="@yield('og_title', 'Gymivida - Gym Management Platform')">
    <meta property="og:description" content="@yield('og_description', 'Choose from our network of premium fitness facilities. Find the perfect gym for your fitness journey with state-of-the-art equipment and expert trainers.')">
    <meta property="og:image" content="@yield('og_image', asset('assets/media/logos/logo.png'))">
    <meta property="og:site_name" content="Gymivida">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'Gymivida - Gym Management Platform')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Choose from our network of premium fitness facilities. Find the perfect gym for your fitness journey.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('assets/media/logos/logo.png'))">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="#009ef7">
    <meta name="msapplication-TileColor" content="#009ef7">
    <meta name="apple-mobile-web-app-title" content="Gymivida">
    <meta name="application-name" content="Gymivida">
    
    <!-- Structured Data for Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Gymivida",
        "description": "Multi-tenant gym management platform connecting fitness enthusiasts with premium gym facilities",
        "url": "{{ config('app.url') }}",
        "logo": "{{ asset('assets/media/logos/logo.png') }}",
        "sameAs": [
            "https://www.facebook.com/Gymivida",
            "https://www.twitter.com/Gymivida",
            "https://www.instagram.com/Gymivida"
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer service",
            "availableLanguage": "English"
        }
    }
    </script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/toastr.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="{{ asset('assets/master-assets/css/style.css') }}" rel="stylesheet">
</head>