<!-- Offcanvas Menu Section Begin -->
<div class="offcanvas-menu-overlay"></div>
<div class="offcanvas-menu-wrapper">
    <div class="canvas-close">
        <i class="fa fa-close"></i>
    </div>
    @auth
        <div class="canvas-notification">
            <i class="fa fa-bell"></i>
            <span class="notification-badge-mobile" id="notificationBadgeMobile" style="display: none;">0</span>
        </div>
    @else
        <div class="canvas-search search-switch">
            <i class="fa fa-search"></i>
        </div>
    @endauth
    <nav class="canvas-menu mobile-menu">
        <ul>
            <li class="{{ request()->routeIs('user.home') ? 'active' : '' }}"><a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">Home</a></li>
            <li class="{{ request()->routeIs('user.about-us') ? 'active' : '' }}"><a href="{{ route('user.about-us', ['siteSetting' => $siteSetting->slug]) }}">About Us</a></li>
            @if($gymFeatures['classes'])
                <li class="{{ request()->routeIs('user.classes.index') ? 'active' : '' }}"><a href="{{ route('user.classes.index', ['siteSetting' => $siteSetting->slug]) }}">Classes</a></li>
            @endif
            @if($gymFeatures['services'])
                <li class="{{ request()->routeIs('user.services.index') ? 'active' : '' }}"><a href="{{ route('user.services.index', ['siteSetting' => $siteSetting->slug]) }}">Services</a></li>
            @endif
            @if($gymFeatures['team'])
                <li class="{{ request()->routeIs('user.team') ? 'active' : '' }}"><a href="{{ route('user.team', ['siteSetting' => $siteSetting->slug]) }}">Our Team</a></li>
            @endif
            @if($gymFeatures['gallery'] || $gymFeatures['blog'])
                <li><a href="#">Pages</a>
                    <ul class="dropdown">
                        @if($gymFeatures['gallery'])
                            <li><a href="{{ route('user.gallery', ['siteSetting' => $siteSetting->slug]) }}">Gallery</a></li>
                        @endif
                        @if($gymFeatures['blog'])
                            <li><a href="{{ route('user.blog', ['siteSetting' => $siteSetting->slug]) }}">Our blog</a></li>
                        @endif
                    </ul>
                </li>
            @endif
            <li><a href="{{route('user.contact', ['siteSetting' => $siteSetting->slug])}}">Contact</a></li>
            @auth
                <li class="{{ request()->routeIs('user.invitations.*') ? 'active' : '' }}"><a href="{{ route('user.invitations.index', ['siteSetting' => $siteSetting->slug]) }}">My Invitations</a></li>
                <li class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><a href="{{ route('profile.index', ['siteSetting' => $siteSetting->slug]) }}">My Profile</a></li>
            @endauth
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="canvas-social">
        <a href="{{$siteSetting->facebook_url}}" target="_blank"><i class="fa fa-facebook"></i></a>
        <a href="{{$siteSetting->x_url}}" target="_blank"><i class="fa fa-twitter"></i></a>
        <a href="{{$siteSetting->instagram_url}}" target="_blank"><i class="fa fa-instagram"></i></a>
    </div>
</div>
<!-- Offcanvas Menu Section End -->

<!-- Header Section Begin -->
<header class="header-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <div class="logo" style="justify-self: center;">
                    <a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">
                        <img src="{{ $siteSetting->getFirstMediaUrl('gym_logo') ?? asset('assets/user/img/logo.png') }}" alt="{{ $siteSetting->gym_name }}" style="height: 70px;border-radius: 50%;">
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="nav-menu">
                    <ul>
                        <li class="{{ request()->routeIs('user.home') ? 'active' : '' }}"><a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">Home</a></li>
                        @if($gymFeatures['classes'])
                            <li class="{{ request()->routeIs('user.classes.index') ? 'active' : '' }}"><a href="{{ route('user.classes.index', ['siteSetting' => $siteSetting->slug]) }}">Classes</a></li>
                        @endif
                        @if($gymFeatures['services'])
                            <li class="{{ request()->routeIs('user.services.index') ? 'active' : '' }}"><a href="{{ route('user.services.index', ['siteSetting' => $siteSetting->slug]) }}">Services</a></li>
                        @endif
                        @php
                            $aboutUsRoutes = ['user.about-us'];
                            if($gymFeatures['team']) $aboutUsRoutes[] = 'user.team';
                            if($gymFeatures['gallery']) $aboutUsRoutes[] = 'user.gallery';
                            if($gymFeatures['blog']) $aboutUsRoutes[] = 'user.blog';
                            $aboutUsActive = request()->routeIs($aboutUsRoutes);
                        @endphp
                        <li class="{{ $aboutUsActive ? 'active' : '' }}"><a href="#">About Us</a>
                            <ul class="dropdown">
                                <li class="{{ request()->routeIs('user.about-us') ? 'active' : '' }}"><a href="{{ route('user.about-us', ['siteSetting' => $siteSetting->slug]) }}">About us</a></li>
                                @if($gymFeatures['team'])
                                    <li class="{{ request()->routeIs('user.team') ? 'active' : '' }}"><a href="{{ route('user.team', ['siteSetting' => $siteSetting->slug]) }}">Our team</a></li>
                                @endif
                                @if($gymFeatures['gallery'])
                                    <li class="{{ request()->routeIs('user.gallery') ? 'active' : '' }}"><a href="{{ route('user.gallery', ['siteSetting' => $siteSetting->slug]) }}">Gallery</a></li>
                                @endif
                                @if($gymFeatures['blog'])
                                    <li class="{{ request()->routeIs('user.blog') ? 'active' : '' }}"><a href="{{ route('user.blog', ['siteSetting' => $siteSetting->slug]) }}">Our blog</a></li>
                                @endif
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
                                @if($gymFeatures['checkin'])
                                    <a class="dropdown-item" href="{{ route('user.checkin.self', ['siteSetting' => $siteSetting->slug]) }}">
                                        <i class="fa fa-check"></i> Gym Entrance
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('user.invitations.index', ['siteSetting' => $siteSetting->slug]) }}">
                                    <i class="fa fa-envelope"></i> My Invitations
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('auth.logout.current', ['siteSetting' => $siteSetting->slug]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <i class="fa fa-sign-out"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="notification-dropdown">
                            <div class="notification-icon" id="notificationToggle">
                                <i class="fa fa-bell"></i>
                                <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                            </div>
                            <div class="notification-dropdown-menu" id="notificationDropdown">
                                <div class="notification-header">
                                    <h6>Notifications</h6>
                                    <button class="mark-all-read-btn" id="markAllReadBtn">Mark all as read</button>
                                </div>
                                <div class="notification-list" id="notificationList">
                                    <div class="notification-loading">Loading notifications...</div>
                                </div>
                                <div class="notification-footer">
                                    <a href="#" class="view-all-notifications">View all notifications</a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="auth-buttons">
                            <a href="{{ route('auth.login.index', ['siteSetting' => $siteSetting->slug]) }}">
                                <i class="fa fa-user text-white"></i>
                            </a>
                        </div>
                        <div class="to-search search-switch">
                            <i class="fa fa-search"></i>
                        </div>
                    @endauth
                    
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