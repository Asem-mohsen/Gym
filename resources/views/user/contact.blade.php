@extends('layout.user.master')

@section('title', 'Contact Us')

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Contact Us</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                            <span>Contact us</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Contact Section Begin -->
    <section class="contact-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-title contact-title">
                        <span>Contact Us</span>
                        <h2>GET IN TOUCH</h2>
                    </div>
                    <div class="contact-widget">
                        <div class="cw-text">
                            <i class="fa fa-map-marker"></i>
                            @foreach ($siteSetting->branches as $branch)
                                <p>{{ $branch->getTranslation('name', app()->getLocale()) . ' - ' . $branch->getTranslation('location', app()->getLocale()) }}</p>
                            @endforeach
                        </div>
                        <div class="cw-text">
                            <i class="fa fa-mobile"></i>
                            <ul>
                                @foreach ($siteSetting->branches as $branch)
                                    @foreach ($branch->phones as $phone)
                                        <li>{{ $phone->phone_number }}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                        <div class="cw-text email">
                            <i class="fa fa-envelope"></i>
                            <p>{{$siteSetting->contact_email}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="leave-comment">
                        <form action="{{ route('user.contact.store' , ['siteSetting' => $siteSetting->slug]) }}" method="post">
                            @csrf
                            <input type="text" placeholder="Name" name="name">
                            <input type="text" placeholder="Email" name="email">
                            <input type="number" placeholder="Phone" name="phone">
                            <textarea placeholder="Message" name="message"></textarea>
                            <button type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            @if($siteSetting->site_map)
                <div class="map">
                    <iframe src="{{$siteSetting->site_map}}" loading="lazy" height="550" style="border:0;" allowfullscreen=""></iframe>
                </div>
            @endif
        </div>
    </section>
    <!-- Contact Section End -->

@endsection