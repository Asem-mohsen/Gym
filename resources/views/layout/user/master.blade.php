<!DOCTYPE html>
<html lang="en">

  @include('layout.user.header.head')

  <body>
        @include('layout.user.preloader.preloader')

        @include('layout.user.navbar.navbar')

          @yield('content')

        @include('layout.user.footer.footer')
        
  </body>

</html>