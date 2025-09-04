<head>
    <title>
        @yield('title','Dashboard')
    </title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />

    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />

    {!! getStyles() !!}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/css/intlTelInput.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Inject gym branding CSS variables --}}
    <x-gym-branding-css />

</head>

@yield('css')