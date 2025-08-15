<!-- Get In Touch Section Begin -->
<div class="gettouch-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="gt-text">
                    <i class="fa fa-map-marker"></i>
                    @foreach ($siteSetting->branches as $branch)
                        <p>{{ $branch->getTranslation('name', app()->getLocale()) . ' - ' . $branch->getTranslation('location', app()->getLocale()) }}</p>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="gt-text">
                    <i class="fa fa-mobile"></i>
                    <ul>
                        @foreach ($siteSetting->branches as $branch)
                            @foreach ($branch->phones as $phone)
                                <li>{{ $phone->phone_number }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="gt-text email">
                    <i class="fa fa-envelope"></i>
                    <p>{{$siteSetting->contact_email}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Get In Touch Section End -->

<!-- Footer Section Begin -->
<section class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="fs-about">
                    <div class="fa-logo">
                        <a href="{{route('user.home' , ['siteSetting' => $siteSetting->slug])}}"><img src="{{ asset('assets/user/img/logo.png') }}" alt=""></a>
                    </div>
                    <p>{{$siteSetting->getTranslation('description', app()->getLocale())}}</p>
                    <div class="fa-social">
                        <a href="{{$siteSetting->facebook_url}}"><i class="fa fa-facebook"></i></a>
                        <a href="{{$siteSetting->x_url}}"><i class="fa fa-twitter"></i></a>
                        <a href="{{$siteSetting->instagram_url}}"><i class="fa fa-instagram"></i></a>
                        <a href="mailto:{{$siteSetting->contact_email}}"><i class="fa  fa-envelope-o"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="fs-widget">
                    <h4>Useful links</h4>
                    <ul>
                        <li><a href="{{route('user.about-us' , ['siteSetting' => $siteSetting->slug])}}">About</a></li>
                        <li><a href="{{route('user.blog' , ['siteSetting' => $siteSetting->slug])}}">Blog</a></li>
                        <li><a href="{{route('user.classes.index' , ['siteSetting' => $siteSetting->slug])}}">Classes</a></li>
                        <li><a href="{{route('user.contact' , ['siteSetting' => $siteSetting->slug])}}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="fs-widget">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="{{route('auth.login.index', ['siteSetting' => $siteSetting->slug])}}">Login</a></li>
                        <li><a href="{{route('auth.register.index', ['siteSetting' => $siteSetting->slug])}}">Register</a></li>
                        <li><a href="{{route('user.contact' , ['siteSetting' => $siteSetting->slug])}}">Subscribe</a></li>
                        <li><a href="{{route('user.contact' , ['siteSetting' => $siteSetting->slug])}}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="fs-widget">
                    <h4>Tips & Guides</h4>
                    @foreach ($blogPosts->take(2) as $blogPost)
                        <div class="fw-recent">
                            <h6><a href="{{route('user.blog.show', ['blogPost' => $blogPost->id, 'siteSetting' => $siteSetting->slug])}}">{{$blogPost->title}}</a></h6>
                            <ul>
                                <li>{{$blogPost->created_at->diffForHumans()}}</li>
                                <li>{{$blogPost->comments_count}} Comment</li>
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="copyright-text">
                    <p>
                        Copyright &copy; {{now()->year}} All rights reserved. <a href="{{route('user.home' , ['siteSetting' => $siteSetting->slug])}}" target="_blank">{{$siteSetting->getTranslation('gym_name', app()->getLocale())}}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Footer Section End -->

<!-- Search model Begin -->
<div class="search-model">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch">+</div>
        <form class="search-model-form">
            <input type="text" id="search-input" placeholder="Search here.....">
        </form>
    </div>
</div>
<!-- Search model end -->

<script src="{{ asset('assets/user/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('assets/user/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/user/js/masonry.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery.barfiller.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('assets/user/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/user/js/main.js') }}"></script>

@include('components.gym-context-handler')

@yield('Js')