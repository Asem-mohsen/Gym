@extends('layout.user.master')

@section('title', $service->getTranslation('name', 'en'))

@section('css')
    @include('user.services.assets.style')
@endsection

@section('content')

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>{{ $service->getTranslation('name', 'en') }}</h2>
                    <div class="bt-option">
                        <a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                        <a href="{{ route('user.services.index', ['siteSetting' => $siteSetting->slug]) }}">Services</a>
                        <span>{{ $service->getTranslation('name', 'en') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<div class="service-details-section pt-5">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="{{ $service->isBookable() ? 'col-lg-8' : 'col-lg-12' }}">
                <!-- Service Details -->
                <div class="service-details-card mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="service-image-container">
                                @if($service->getFirstMediaUrl('service_image'))
                                    <img src="{{ $service->getFirstMediaUrl('service_image') }}" 
                                         alt="{{ $service->getTranslation('name', 'en') }}" 
                                         class="img-fluid rounded service-main-image">
                                @else
                                    <div class="service-placeholder">
                                        <i class="fa fa-image fa-3x"></i>
                                        <p>No image available</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-info">
                                <h1 class="service-title">{{ $service->getTranslation('name', 'en') }}</h1>
                                <p class="service-subtitle">{{ $service->getTranslation('name', 'ar') }}</p>
                                
                                <div class="service-meta">
                                    @if($service->duration > 0)
                                        <div class="meta-item">
                                            <i class="fa fa-clock-o"></i>
                                            <span>{{ $service->duration }} minutes</span>
                                        </div>
                                    @endif
                                    
                                    <div class="meta-item">
                                        <i class="fa fa-money"></i>
                                        @if($service->price > 0)
                                            <span>{{ number_format($service->price, 2) }} EGP</span>
                                        @else
                                            <span class="text-success">Free</span>
                                        @endif
                                    </div>
                                    
                                    <div class="meta-item">
                                        <i class="fa fa-calendar"></i>
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
                                
                                <div class="service-description">
                                    <h4>Description</h4>
                                    <p>{{ $service->getTranslation('description', 'en') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Branches -->
                @if($service->branches->count() > 0)
                    <div class="branches-section mb-4">
                        <h3>Available Branches</h3>
                        <div class="row">
                            @foreach($service->branches as $branch)
                                <div class="col-md-6 mb-3">
                                    <div class="branch-card">
                                        <div class="branch-info">
                                            <h5>{{ $branch->getTranslation('name', 'en') }}</h5>
                                            <p class="branch-location">
                                                <i class="fa fa-map-marker"></i>
                                                {{ $branch->getTranslation('location', 'en') }}
                                            </p>
                                            <p class="branch-type">
                                                <i class="fa fa-building"></i>
                                                {{ ucfirst($branch->type) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Service Gallery -->
                @if($service->galleries->count() > 0)
                    <div class="gallery-section mb-4">
                        <h3>Gallery</h3>
                        @foreach($service->galleries as $gallery)
                            <div class="gallery-container mb-4">
                                @if($gallery->getMedia('gallery_images')->count() > 0)
                                    <div class="row">
                                        @foreach($gallery->getMedia('gallery_images') as $media)
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="gallery-item">
                                                    <img src="{{ $media->getUrl() }}" 
                                                         alt="{{ $media->getCustomProperty('alt_text', $media->file_name) }}"
                                                         class="img-fluid rounded gallery-image">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-white py-3">
                                        <i class="fa fa-images fa-2x mb-2"></i>
                                        <p>No images in this gallery</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Booking Sidebar -->
            @if($service->isBookable())
                <div class="col-lg-4">
                    <div class="booking-sidebar">
                        <div class="booking-card">
                            <h4>Book This Service</h4>
                            
                            @if($service->requiresBookingPayment())
                                <div class="booking-price">
                                    <span class="price-label">Price:</span>
                                    <span class="price-value">{{ number_format($service->price, 2) }} EGP</span>
                                </div>
                            @endif

                            <form id="bookingForm" action="{{ route('user.checkout.create', ['siteSetting' => $siteSetting->slug]) }}" method="POST" class="payment-form">
                                @csrf
                                <input type="hidden" name="bookable_type" value="service">
                                <input type="hidden" name="bookable_id" value="{{ $service->id }}">
                                <input type="hidden" name="method" value="card">
                                
                                <!-- Branch Selection -->
                                @if($service->branches->count() > 1)
                                    <div class="form-group mb-3">
                                        <label for="branch_id" class="form-label">Select Branch *</label>
                                        <select name="branch_id" id="branch_id" class="form-control" required>
                                            <option value="">Choose a branch</option>
                                            @foreach($service->branches as $branch)
                                                <option value="{{ $branch->id }}">
                                                    {{ $branch->getTranslation('name', 'en') }} - {{ $branch->getTranslation('location', 'en') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif($service->branches->count() == 1)
                                    <input type="hidden" name="branch_id" value="{{ $service->branches->first()->id }}">
                                @endif

                                <!-- Booking Date -->
                                <div class="form-group mb-3">
                                    <label for="booking_date" class="form-label">Booking Date *</label>
                                    <input type="date" name="booking_date" id="booking_date" class="form-control" min="{{ date('Y-m-d') }}" required>
                                </div>

                                <!-- Payment Method (only for paid bookings) -->
                                @if($service->requiresBookingPayment())
                                    <div class="form-group mb-3">
                                        <label class="form-label">Payment Method *</label>
                                        <div class="payment-options">
                                            <div class="payment-option">
                                                <input class="payment-radio" type="radio" name="method" id="payment_cash" value="cash" required>
                                                <label class="payment-label" for="payment_cash">
                                                    <i class="fa fa-money"></i> Cash Payment
                                                </label>
                                            </div>
                                            <div class="payment-option">
                                                <input class="payment-radio" type="radio" name="method" id="payment_card" value="card" required>
                                                <label class="payment-label" for="payment_card">
                                                    <i class="fa fa-credit-card"></i> Card Payment
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="method" value="cash">
                                    <input type="hidden" name="is_free" value="1">
                                @endif

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary btn-block w-100">
                                    @if($service->requiresBookingPayment())
                                        <i class="fa fa-credit-card"></i> Book & Pay Now
                                    @else
                                        <i class="fa fa-calendar"></i> Book Now
                                    @endif
                                </button>
                            </form>

                            <div class="booking-info mt-3">
                                <small class="text-white">
                                    <i class="fa fa-info-circle"></i>
                                    @if($service->requiresBookingPayment())
                                        Payment will be processed after booking confirmation.
                                    @else
                                        This is a free booking. No payment required.
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('Js')
    @include('user.services.assets.script')
@endsection