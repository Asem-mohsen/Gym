<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    @include('layout.intial-screen.header.header')

    <body>

        @yield('content')

        @include('layout.intial-screen.footer.footer')
    </body>
</html>