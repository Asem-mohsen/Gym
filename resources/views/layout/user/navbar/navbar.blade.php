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
            <li class="{{ request()->routeIs('user.home') ? 'active' : '' }}"><a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">Home</a></li>
            <li class="{{ request()->routeIs('user.about-us') ? 'active' : '' }}"><a href="{{ route('user.about-us', ['siteSetting' => $siteSetting->slug]) }}">About Us</a></li>
            <li class="{{ request()->routeIs('user.classes.index') ? 'active' : '' }}"><a href="{{ route('user.classes.index', ['siteSetting' => $siteSetting->slug]) }}">Classes</a></li>
            <li class="{{ request()->routeIs('user.services.index') ? 'active' : '' }}"><a href="{{ route('user.services.index', ['siteSetting' => $siteSetting->slug]) }}">Services</a></li>
            <li class="{{ request()->routeIs('user.team') ? 'active' : '' }}"><a href="{{ route('user.team', ['siteSetting' => $siteSetting->slug]) }}">Our Team</a></li>
            <li><a href="#">Pages</a>
                <ul class="dropdown">
                    <li ><a href="{{ route('user.gallery', ['siteSetting' => $siteSetting->slug]) }}">Gallery</a></li>
                    {{-- <li><a href="{{route('user.bmi-calculator')}}">Bmi calculate</a></li> --}}
                    <li><a href="{{ route('user.blog', ['siteSetting' => $siteSetting->slug]) }}">Our blog</a></li>
                    <li><a href="{{route('user.gallery' , ['siteSetting' => $siteSetting->slug])}}">Gallery</a></li>
                    <li><a href="{{route('user.blog' , ['siteSetting' => $siteSetting->slug])}}">Our blog</a></li>
                </ul>
            </li>
            <li><a href="{{route('user.contact', ['siteSetting' => $siteSetting->slug])}}">Contact</a></li>
            @auth
                <li class="{{ request()->routeIs('user.invitations.*') ? 'active' : '' }}"><a href="{{ route('user.invitations.index', ['siteSetting' => $siteSetting->slug]) }}">My Invitations</a></li>
                <li class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><a href="{{ route('profile.index', ['siteSetting' => $siteSetting->slug]) }}">My Profile</a></li>
            @endauth
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
                        <li class="{{ request()->routeIs('user.home') ? 'active' : '' }}"><a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">Home</a></li>
                        <li class="{{ request()->routeIs('user.classes.index') ? 'active' : '' }}"><a href="{{ route('user.classes.index', ['siteSetting' => $siteSetting->slug]) }}">Classes</a></li>
                        <li class="{{ request()->routeIs('user.services.index') ? 'active' : '' }}"><a href="{{ route('user.services.index', ['siteSetting' => $siteSetting->slug]) }}">Services</a></li>
                        <li class="{{ request()->routeIs('user.about-us') || request()->routeIs('user.team') || request()->routeIs('user.gallery') || request()->routeIs('user.blog') || request()->routeIs('user.team') ? 'active' : '' }}"><a href="#">About Us</a>
                            <ul class="dropdown">
                                <li class="{{ request()->routeIs('user.about-us') ? 'active' : '' }}"><a href="{{ route('user.about-us', ['siteSetting' => $siteSetting->slug]) }}">About us</a></li>
                                {{-- <li><a href="{{ route('user.bmi-calculator') }}">Bmi calculate</a></li> --}}
                                <li class="{{ request()->routeIs('user.team') ? 'active' : '' }}"><a href="{{ route('user.team', ['siteSetting' => $siteSetting->slug]) }}">Our team</a></li>
                                <li class="{{ request()->routeIs('user.gallery') ? 'active' : '' }}"><a href="{{ route('user.gallery', ['siteSetting' => $siteSetting->slug]) }}">Gallery</a></li>
                                <li class="{{ request()->routeIs('user.blog') ? 'active' : '' }}"><a href="{{ route('user.blog', ['siteSetting' => $siteSetting->slug]) }}">Our blog</a></li>
                            </ul>
                        </li>
                        <li class="{{ request()->routeIs('user.contact') ? 'active' : '' }}"><a href="{{ route('user.contact', ['siteSetting' => $siteSetting->slug]) }}">Contact</a></li>
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3">
                <div class="top-option">
                    
                    @auth
                        <div class="user-profile-dropdown">
                            <div class="user-avatar" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user"></i>
                                <span class="user-name">{{ auth()->user()->name }}</span>
                                <i class="fa fa-chevron-down"></i>
                            </div>
                            <div class="dropdown-menu" aria-labelledby="userProfileDropdown">
                                <div class="dropdown-header">
                                    <div class="user-info">
                                        <div class="user-avatar-small">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <div class="user-details">
                                            <h6>{{ auth()->user()->name }}</h6>
                                            <small>{{ auth()->user()->email }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('profile.index', ['siteSetting' => $siteSetting->slug]) }}">
                                    <i class="fa fa-user-circle"></i> My Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('profile.edit', ['siteSetting' => $siteSetting->slug]) }}">
                                    <i class="fa fa-edit"></i> Edit Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('user.invitations.index', ['siteSetting' => $siteSetting->slug]) }}">
                                    <i class="fa fa-envelope"></i> My Invitations
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('auth.logout.current') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <i class="fa fa-sign-out"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="auth-buttons">
                            <a href="{{ route('auth.login.index', ['siteSetting' => $siteSetting->slug]) }}" class="btn-outline-primary">
                                <i class="fa fa-user"></i>
                            </a>
                        </div>
                    @endauth
                    
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


@include('layout.user.navbar.assets.script')