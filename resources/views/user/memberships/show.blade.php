@extends('layout.user.master')

@section('title', $membership->name)

@section('css')
    @include('user.memberships.assets.style')
@endsection

@section('content')

    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>{{ $membership->name }}</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                            <a href="{{ route('user.memberships.index' , ['siteSetting' => $siteSetting->slug]) }}">Memberships</a>
                            <span>{{ $membership->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Membership Details Section Begin -->
    <section class="pricing-section spad">
        <div class="container">
            <div class="row">
                <!-- Left Column - Membership Details -->
                <div class="col-lg-8">
                    <div class="membership-details-card">
                        <!-- Header with Price -->
                        <div class="membership-header text-center mb-4">
                            <h1 class="membership-title mb-3">{{ $membership->name }} Membership</h1>
                            <div class="price-section">
                                <div class="price-amount">EGP {{ number_format($membership->price, 2) }}</div>
                                <div class="price-period">{{ $membership->period }}</div>
                            </div>
                        </div>

                        @if($userSubscription)
                            <div class="alert subscription-status-alert mb-4">
                                <div class="d-flex align-items-center text-center justify-content-center">
                                    <div>
                                        <h5 class="mb-1 text-white">You're Already Subscribed!</h5>
                                        <p class="mb-2 text-white">Your subscription is active and will expire on <strong>{{ \Carbon\Carbon::parse($userSubscription->end_date)->format('F j, Y') }}</strong></p>
                                        <div class="subscription-details">
                                            <small class=" text-white">
                                                <strong>Branch:</strong> {{ $userSubscription->branch->name ?? 'N/A' }} |
                                                <strong>Start Date:</strong> {{ \Carbon\Carbon::parse($userSubscription->start_date)->format('M j, Y') }} |
                                                <strong>Days Remaining:</strong> {{ \Carbon\Carbon::parse($userSubscription->end_date)->diffInDays(now()) }} days
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($membership->general_description)
                            <!-- Membership Description -->
                            <div class="membership-description mb-4">
                                <h4 class="section-title mb-3">
                                    About This Membership
                                </h4>
                                <div class="description-content">
                                    {{$membership->general_description}}
                                </div>
                            </div>
                        @endif

                        <!-- Special Offers -->
                        @if($membership->offers->count() > 0)
                            <div class="offers-section mb-4">
                                <h4 class="section-title mb-3">
                                    <i class="fa fa-gift text-success me-2"></i>
                                    Special Offers Available!
                                </h4>
                                <div class="offers-grid">
                                    @foreach($membership->offers as $offer)
                                        <div class="offer-card">
                                            <div class="offer-badge">{{ $offer->title }}</div>
                                            <p class="offer-description">{{ $offer->description }}</p>
                                            @if($offer->remaining_days)
                                                <div class="offer-expiry">
                                                    <i class="fa fa-clock text-danger me-1"></i>
                                                    Expires in {{ $offer->remaining_days }} days
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="action-section text-center">
                            @auth
                                @if(!$userSubscription)
                                    <form id="payment-form" action="{{ route('user.payments.paymob.initialize', ['siteSetting' => $siteSetting->slug]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="membership_id" value="{{ $membership->id }}">
                                        <input type="hidden" name="offer_id" value="{{ $membership->offers->first()?->id ?? "" }}">
                                        <input type="hidden" name="site_setting_id" value="{{ $siteSetting->id }}">
                                        <button type="submit" class="btn btn-primary btn-lg enroll-btn" id="enroll-btn">
                                            <span id="button-text">
                                                <i class="fa fa-credit-card me-2"></i>
                                                Enroll Now
                                            </span>
                                            <div id="button-spinner" class="spinner-border spinner-border-sm d-none" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </button>
                                    </form>
                                @else
                                    <div class="already-subscribed-message">
                                        <button class="btn btn-success btn-lg" disabled>
                                            <i class="fa fa-check-circle me-2"></i>
                                            Already Subscribed
                                        </button>
                                        <p class="text-muted mt-2">You can renew your subscription when it expires</p>
                                    </div>
                                @endif
                            @else
                                <div class="guest-message">
                                    <a href="{{ route('auth.register.index') }}" class="btn btn-primary btn-lg">
                                        <i class="fa fa-user-plus me-2"></i>
                                        Join Us to Enroll
                                    </a>
                                    <p class="text-muted mt-2">Create an account to access this membership</p>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Right Column - Features -->
                <div class="col-lg-4">
                    <div class="features-sidebar">
                        <div class="features-card">
                            <div class="features-header">
                                <h3 class="features-title">
                                    <i class="fa fa-list-check text-primary me-2"></i>
                                    Membership Features
                                </h3>
                                <p class="features-subtitle">Everything included in your membership</p>
                            </div>
                            
                            <div class="features-list">
                                @forelse ($membership->features as $feature)
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fa fa-check-circle text-success"></i>
                                        </div>
                                        <div class="feature-content">
                                            <h6 class="feature-name">{{ $feature->name }}</h6>
                                            @if($feature->description)
                                                <p class="feature-description">{{ $feature->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="no-features">
                                        <i class="fa fa-info-circle text-muted"></i>
                                        <p class="text-white">No specific features listed for this membership</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Membership Summary -->
                            <div class="membership-summary">
                                <h5 class="summary-title">Membership Summary</h5>
                                <div class="summary-item">
                                    <span class="summary-label">Duration:</span>
                                    <span class="summary-value">{{ $membership->period }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Billing:</span>
                                    <span class="summary-value">{{ ucfirst($membership->billing_interval) }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="summary-label">Features:</span>
                                    <span class="summary-value">{{ $membership->features->count() }} included</span>
                                </div>
                                @if($membership->offers->count() > 0)
                                    <div class="summary-item">
                                        <span class="summary-label">Offers:</span>
                                        <span class="summary-value text-success">{{ $membership->offers->count() }} available</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Membership Details Section End -->

    <!-- Trainers Section Begin -->
    @if($trainers->count() > 0)
        <section class="team-section spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title border-0">
                            <span>Our Expert Trainers</span>
                            <h2>TRAIN WITH EXPERTS</h2>
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
    @endif
    <!-- Trainers Section End -->

    <x-user.branch-selection-helper />
@endsection

@section('Js')
    @include('user.memberships.assets.scripts')
@endsection
