<!DOCTYPE html>
<html lang="en">

  @include('layout.header.head')

  <body class="hold-transition sidebar-mini layout-fixed">
    
    <div class="wrapper">

        @include('layout.preloader.preloader')

        @include('layout.navbar.navbar')

        @include('layout.sidebar.sidebar')

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
              <div class="container-fluid">
                <div class="row mb-2">
                  <div class="col-sm-6">
                    <h1 class="m-0">@yield('title')</h1>
                  </div>
                  <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Home</a></li>
                      <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                  </div>
                </div>
              </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    @include('layout.footer.footer')
  </body>
</html>