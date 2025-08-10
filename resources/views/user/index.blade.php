@extends('layout.user.master')

@section('title', 'Home')

@section('content')

    <!-- Hero Section Begin -->
    <section class="hero-section">
        <div class="hs-slider owl-carousel">
            <div class="hs-item set-bg" data-setbg="{{ asset('assets/user/img/hero/hero-1.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-6">
                            <div class="hi-text">
                                <span>Shape your body</span>
                                <h1>Be <strong>strong</strong> traning hard</h1>
                                <a href="#" class="primary-btn">Get info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hs-item set-bg" data-setbg="{{ asset('assets/user/img/hero/hero-2.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-6">
                            <div class="hi-text">
                                <span>Shape your body</span>
                                <h1>Be <strong>strong</strong> traning hard</h1>
                                <a href="#" class="primary-btn">Get info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- ChoseUs Section Begin -->
    <section class="choseus-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Why chose us?</span>
                        <h2>PUSH YOUR LIMITS FORWARD</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="cs-item">
                        <span class="flaticon-034-stationary-bike"></span>
                        <h4>Modern equipment</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            dolore facilisis.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="cs-item">
                        <span class="flaticon-033-juice"></span>
                        <h4>Healthy nutrition plan</h4>
                        <p>Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel
                            facilisis.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="cs-item">
                        <span class="flaticon-002-dumbell"></span>
                        <h4>Proffesponal training plan</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            dolore facilisis.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="cs-item">
                        <span class="flaticon-014-heart-beat"></span>
                        <h4>Unique to your needs</h4>
                        <p>Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel
                            facilisis.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ChoseUs Section End -->

    <!-- Classes Section Begin -->
    <section class="classes-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Our Classes</span>
                        <h2>WHAT WE CAN OFFER</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($classes as $index => $class)
                    <div class="col-lg-{{ in_array($index, [3, 4]) ? '6' : '4' }} col-md-6">
                        <div class="class-item">
                            <div class="ci-pic">
                                <img src="{{ $class->getFirstMediaUrl('class_images') }}" alt="">
                            </div>
                            <div class="ci-text">
                                <span>{{ $class->type }}</span>
                                <h5>{{ $class->name }}</h5>
                                <a href="{{ route('user.classes.show', ['siteSetting' => $siteSetting->slug, 'class' => $class->id]) }}"><i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- <div class="col-lg-4 col-md-6">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="{{ asset('assets/user/img/classes/class-2.jpg') }}" alt="">
                        </div>
                        <div class="ci-text">
                            <span>Cardio</span>
                            <h5>Indoor cycling</h5>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="{{ asset('assets/user/img/classes/class-3.jpg') }}" alt="">
                        </div>
                        <div class="ci-text">
                            <span>STRENGTH</span>
                            <h5>Kettlebell power</h5>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="{{ asset('assets/user/img/classes/class-4.jpg') }}" alt="">
                        </div>
                        <div class="ci-text">
                            <span>Cardio</span>
                            <h4>Indoor cycling</h4>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="class-item">
                        <div class="ci-pic">
                            <img src="{{ asset('assets/user/img/classes/class-5.jpg') }}" alt="">
                        </div>
                        <div class="ci-text">
                            <span>Training</span>
                            <h4>Boxing</h4>
                            <a href="#"><i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    <!-- ChoseUs Section End -->

    <!-- Banner Section Begin -->
    <section class="banner-section set-bg" data-setbg="{{ asset('assets/user/img/banner-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="bs-text">
                        <h2>register now to get more deals</h2>
                        <div class="bt-tips">Where health, beauty and fitness meet.</div>
                        <a href="{{ route('user.contact' , ['siteSetting' => $siteSetting->slug]) }}" class="primary-btn  btn-normal">Appointment</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->

    <!-- Pricing Membership Section Begin -->
    @if ($memberships->count() > 0)
        <section class="pricing-section spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <span>Our Plan</span>
                            <h2>Choose your pricing plan</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @foreach ($memberships as $membership)
                        <div class="col-lg-4 col-md-8">
                            <div class="ps-item">
                                <h3>{{ $membership->name }}</h3>
                                <div class="pi-price">
                                    <h2>$ {{ $membership->price }}</h2>
                                    <span>{{ $membership->duration }}</span>
                                </div>
                                <ul>
                                    @foreach ($membership->features as $feature)
                                        <li>{{ $feature->name }}</li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('user.memberships.show', ['siteSetting' => $siteSetting->slug, 'membership' => $membership->id]) }}" class="primary-btn pricing-btn">Enroll now</a>
                                <a href="{{ route('user.memberships.show', ['siteSetting' => $siteSetting->slug, 'membership' => $membership->id]) }}" class="thumb-icon"><i class="fa fa-picture-o"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- Pricing Membership Section End -->

    <!-- Gallery Section Begin -->
    @if ($galleries->count() > 0)
        <div class="gallery-section">
            <div class="gallery">
                <div class="grid-sizer"></div>
                @foreach ($galleries as $gallery)
                    @foreach ($gallery['media'] as $index => $media)
                        @php
                            $isGridWide = in_array($index, [0, 5]);
                        @endphp
                        <div class="gs-item {{ $isGridWide ? 'grid-wide' : '' }} set-bg" data-setbg="{{ $media['original_url'] }}">
                            <a href="{{ $media['original_url'] }}" class="thumb-icon image-popup">
                                <i class="fa fa-picture-o"></i>
                            </a>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif
    <!-- Gallery Section End -->

    <!-- Team Section Begin -->
    <section class="team-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="team-title">
                        <div class="section-title">
                            <span>Our Team</span>
                            <h2>TRAIN WITH EXPERTS</h2>
                        </div>
                        <a href="{{ route('user.contact' , ['siteSetting' => $siteSetting->slug]) }}" class="primary-btn btn-normal appoinment-btn">appointment</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="ts-slider owl-carousel">
                    @foreach ($trainers as $trainer)
                        <x-our-team-card :trainer="$trainer" />
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Team Section End -->

@endsection