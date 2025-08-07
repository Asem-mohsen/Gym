<!-- Offcanvas Menu Section Begin -->
<div class="offcanvas-menu-overlay"></div>
<div class="offcanvas-menu-wrapper">
    <div class="canvas-close">
        <i class="fa fa-close"></i>
    </div>
    <div class="canvas-search search-switch">
        <i class="fa fa-search"></i>
    </div>
    <nav class="canvas-menu mobile-menu">
        <ul>
            <li><a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">Home</a></li>
            <li><a href="{{ route('user.about-us', ['siteSetting' => $siteSetting->slug]) }}">About Us</a></li>
            <li><a href="{{ route('user.classes.index', ['siteSetting' => $siteSetting->slug]) }}">Classes</a></li>
            <li><a href="{{ route('user.services', ['siteSetting' => $siteSetting->slug]) }}">Services</a></li>
            <li><a href="{{ route('user.team', ['siteSetting' => $siteSetting->slug]) }}">Our Team</a></li>
            <li><a href="#">Pages</a>
                <ul class="dropdown">
                    <li><a href="{{ route('user.gallery', ['siteSetting' => $siteSetting->slug]) }}">Gallery</a></li>
                    {{-- <li><a href="{{route('user.bmi-calculator')}}">Bmi calculate</a></li> --}}
                    <li><a href="{{ route('user.blog', ['siteSetting' => $siteSetting->slug]) }}">Our blog</a></li>
                    <li><a href="{{route('user.gallery' , ['siteSetting' => $siteSetting->slug])}}">Gallery</a></li>
                    <li><a href="{{route('user.blog' , ['siteSetting' => $siteSetting->slug])}}">Our blog</a></li>
                </ul>
            </li>
            <li><a href="{{route('user.contact', ['siteSetting' => $siteSetting->slug])}}">Contact</a></li>
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="canvas-social">
        <a href="{{$siteSetting->facebook_url}}"><i class="fa fa-facebook"></i></a>
        <a href="{{$siteSetting->x_url}}"><i class="fa fa-twitter"></i></a>
        <a href="{{$siteSetting->instagram_url}}"><i class="fa fa-instagram"></i></a>
    </div>
</div>
<!-- Offcanvas Menu Section End -->

<!-- Header Section Begin -->
<header class="header-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <div class="logo">
                    <a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">
                        <img src="{{ asset('assets/user/img/logo.png') }}" alt="">
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="nav-menu">
                    <ul>
                        <li class="active"><a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">Home</a></li>
                        <li><a href="{{ route('user.classes.index', ['siteSetting' => $siteSetting->slug]) }}">Classes</a></li>
                        <li><a href="{{ route('user.services', ['siteSetting' => $siteSetting->slug]) }}">Services</a></li>
                        <li><a href="#">About Us</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('user.about-us', ['siteSetting' => $siteSetting->slug]) }}">About us</a></li>
                                <li><a href="{{ route('user.classes', ['siteSetting' => $siteSetting->slug]) }}">Classes timetable</a></li>
                                {{-- <li><a href="{{ route('user.bmi-calculator') }}">Bmi calculate</a></li> --}}
                                <li><a href="{{ route('user.team', ['siteSetting' => $siteSetting->slug]) }}">Our team</a></li>
                                <li><a href="{{ route('user.gallery', ['siteSetting' => $siteSetting->slug]) }}">Gallery</a></li>
                                <li><a href="{{ route('user.blog', ['siteSetting' => $siteSetting->slug]) }}">Our blog</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('user.contact', ['siteSetting' => $siteSetting->slug]) }}">Contact</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="top-option">
                    <div class="to-search search-switch">
                        <i class="fa fa-search"></i>
                    </div>
                    <div class="to-social">
                        <a href="{{$siteSetting->facebook_url}}"><i class="fa fa-facebook"></i></a>
                        <a href="{{$siteSetting->x_url}}"><i class="fa fa-twitter"></i></a>
                        <a href="{{$siteSetting->instagram_url}}"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="canvas-open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
<!-- Header End -->
