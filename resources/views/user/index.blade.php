@extends('layout.user.master')

@section('title', 'Home')

@section('content')

    <!-- Hero Section Begin -->
    <section class="hero-section">
        <div class="hs-slider owl-carousel">
            
            @php
                $heroImages = $branding['media_urls']['hero_banner'] ?? [];
                $heroData = $branding['repeater_fields']['home_hero'] ?? [];
                $heroImage1 = is_array($heroImages) && isset($heroImages[0]) ? $heroImages[0] : asset('assets/user/img/hero/hero-1.jpg');
                $heroImage2 = is_array($heroImages) && isset($heroImages[1]) ? $heroImages[1] : asset('assets/user/img/hero/hero-2.jpg');
            @endphp
            
            @if(count($heroData) > 0)
                @foreach($heroData as $index => $heroItem)
                    @php
                        $heroImage = $index == 0 ? $heroImage1 : $heroImage2;
                    @endphp
                    <div class="hs-item set-bg" data-setbg="{{ $heroImage }}">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6 offset-lg-6">
                                    <div class="hi-text">
                                        <span>{{ $heroItem['subtitle'] ?? 'Shape your body' }}</span>
                                        <h1>{{ $heroItem['title'] ?? 'Be <strong>strong</strong> training hard' }}</h1>
                                        @php
                                            $buttonLink = \App\Models\GymSetting::convertPageNameToUrl($heroItem['button_link'] ?? '#', $siteSetting->slug);
                                        @endphp
                                        <a href="{{ $buttonLink }}" class="primary-btn">{{ $heroItem['button_text'] ?? 'Get info' }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="hs-item set-bg" data-setbg="{{ $heroImage1 }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-6">
                                <div class="hi-text">
                                    <span>Shape your body</span>
                                    <h1>Be <strong>strong</strong> training hard</h1>
                                    <a href="#" class="primary-btn">Get info</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hs-item set-bg" data-setbg="{{ $heroImage2 }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-6">
                                <div class="hi-text">
                                    <span>Shape your body</span>
                                    <h1>Be <strong>strong</strong> training hard</h1>
                                    <a href="#" class="primary-btn">Get info</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                @php
                    $choseusData = $branding['repeater_fields']['home_choseus'] ?? [];
                @endphp
                
                @if(count($choseusData) > 0)
                    @foreach($choseusData as $item)
                        <div class="col-lg-3 col-sm-6">
                            <div class="cs-item">
                                <span class="{{ $item['icon'] ?? 'flaticon-034-stationary-bike' }}"></span>
                                <h4>{{ $item['title'] ?? 'Modern equipment' }}</h4>
                                <p>{{ $item['description'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut dolore facilisis.' }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
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
                            <h4>Professional training plan</h4>
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
                @endif
            </div>
        </div>
    </section>
    <!-- ChoseUs Section End -->

    <!-- Branches Section Begin -->
    @if ($branches->count() > 0)
        <section class="branches-section spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <span>Our Branches</span>
                            <h2>FIND US NEAR YOU</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @foreach ($branches as $branch)
                        <div class="col-lg-4 col-md-6">
                            <div class="branch-item">
                                <div class="bi-pic">
                                    @if($branch->getFirstMediaUrl('branch_images'))
                                        <img src="{{ $branch->getFirstMediaUrl('branch_images') }}" class="branch-image" alt="{{ $branch->name }}">
                                    @else
                                        <img src="{{ asset('assets/user/img/hero/hero-1.jpg') }}" class="branch-image" alt="{{ $branch->name }}">
                                    @endif
                                </div>
                                <div class="bi-text mt-3">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <h4 class="text-white">{{ $branch->name }}</h4>
                                        <span class="badge badge-{{ $branch->type === 'mix' ? 'primary' : ($branch->type === 'women' ? 'pink' : 'info') }}">
                                            {{ ucfirst($branch->type) }}
                                        </span>
                                    </div>
                                    <p class="text-white mt-3">{{ $branch->location }}</p>
                                    <a href="{{ route('user.branches.show', ['siteSetting' => $siteSetting->slug, 'branch' => $branch->id]) }}" class="primary-btn">Visit Branch</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- Branches Section End -->

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
            </div>
        </div>
    </section>
    <!-- ChoseUs Section End -->

    <!-- Banner Section Begin -->
    @php
        $bannerImage = $branding['media_urls']['banner_section_bg'] ?? asset('assets/user/img/banner-bg.jpg');
    @endphp
    <section class="banner-section set-bg" data-setbg="{{ $bannerImage }}">
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
                                <a href="{{ route('user.memberships.show', ['siteSetting' => $siteSetting->slug, 'membership' => $membership->id]) }}" class="primary-btn pricing-btn">Discover now</a>
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