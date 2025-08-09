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
            <div class="map">
                <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1353.1852343102355!2d31.331029873491108!3d29.848564417779315!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x145837d4f9003069%3A0x61416eb76fcaabcb!2sNitro%20Gym!5e1!3m2!1sen!2seg!4v1752940179061!5m2!1sen!2seg" loading="lazy" 
                    height="550" style="border:0;" allowfullscreen=""></iframe>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

@endsection