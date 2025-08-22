@extends('layout.user.master')

@section('title', 'Services')

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>Services</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                            <span>Services</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Services Section Begin -->
    <section class="services-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>What we do?</span>
                        <h2>PUSH YOUR LIMITS FORWARD</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse($services as $service)
                    @php
                        // Group index: every 2 services belong to the same group
                        $groupIndex = intdiv($loop->iteration - 1, 2);
            
                        // If groupIndex is even => image first, else => text first
                        $isImageFirst = $groupIndex % 2 === 0;
                    @endphp
            
                    @if($isImageFirst)
                        {{-- Image first --}}
                        <div class="col-lg-3 col-md-6 p-0 position-relative">
                            <div class="ss-pic">
                                <div class="service-overlay position-absolute top-0 start-0 w-100 ml-1">
                                    <div class="service-badge">
                                        @switch($service->booking_type)
                                            @case('unbookable')
                                                <span class="badge bg-info">No Booking Required</span>
                                                @break
                                            @case('free_booking')
                                                <span class="badge bg-success">Free Booking</span>
                                                @break
                                            @case('paid_booking')
                                                <span class="badge bg-warning">Paid Booking</span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                                @if($service->getFirstMediaUrl('service_image'))
                                    <img src="{{ $service->getFirstMediaUrl('service_image') }}" 
                                         alt="{{ $service->getTranslation('name', 'en') }}" 
                                         class="img-fluid">
                                @else
                                    <img src="{{ asset('assets/user/img/services/services-1.jpg') }}" 
                                         alt="{{ $service->getTranslation('name', 'en') }}" 
                                         class="img-fluid">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 p-0">
                            <div class="ss-text">
                                <p>Duration: <span>{{ $service->duration }} minutes</span></p>
                                <h4>{{ $service->getTranslation('name', 'en') }}</h4>
                                <p>{{ Str::limit($service->getTranslation('description', 'en'), 170) }}</p>
                                <a href="{{ route('user.services.show', ['siteSetting' => $siteSetting->slug, 'service' => $service->id]) }}">
                                    Explore
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- Text first --}}
                        <div class="col-lg-3 col-md-6 p-0">
                            <div class="ss-text reverse">
                                <p>Duration: <span>{{ $service->duration }} minutes</span></p>
                                <h4>{{ $service->getTranslation('name', 'en') }}</h4>
                                <p>{{ Str::limit($service->getTranslation('description', 'en'), 170) }}</p>
                                <a href="{{ route('user.services.show', ['siteSetting' => $siteSetting->slug, 'service' => $service->id]) }}">
                                    Explore
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 p-0 position-relative">
                            <div class="ss-pic">
                                <div class="service-overlay position-absolute top-0 start-0 w-100 ml-1">
                                    <div class="service-badge">
                                        @switch($service->booking_type)
                                            @case('unbookable')
                                                <span class="badge bg-info">No Booking Required</span>
                                                @break
                                            @case('free_booking')
                                                <span class="badge bg-success">Free Booking</span>
                                                @break
                                            @case('paid_booking')
                                                <span class="badge bg-warning">Paid Booking</span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                                @if($service->getFirstMediaUrl('service_image'))
                                    <img src="{{ $service->getFirstMediaUrl('service_image') }}" 
                                         alt="{{ $service->getTranslation('name', 'en') }}" 
                                         class="img-fluid">
                                @else
                                    <img src="{{ asset('assets/user/img/services/services-1.jpg') }}" 
                                         alt="{{ $service->getTranslation('name', 'en') }}" 
                                         class="img-fluid">
                                @endif
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-12 text-center">
                        <div class="no-services">
                            <i class="fa fa-info-circle fa-3x text-muted mb-3"></i>
                            <h4>No Services Available</h4>
                            <p class="text-muted">We're currently setting up our services. Please check back soon!</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
        </div>
    </section>
    <!-- Services Section End -->

    <!-- Banner Section Begin -->
    <section class="banner-section set-bg" data-setbg="{{ asset('assets/user/img/banner-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="bs-text service-banner">
                        <h2>Exercise until the body obeys.</h2>
                        <div class="bt-tips">Where health, beauty and fitness meet.</div>
                        <a href="https://www.youtube.com/watch?v=EzKkl64rRbM" class="play-btn video-popup"><i
                                class="fa fa-caret-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->

    <!-- Pricing Section Begin -->
    @if ($memberships->count() > 0)
        <section class="pricing-section service-pricing spad">
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
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- Pricing Section End -->

@endsection