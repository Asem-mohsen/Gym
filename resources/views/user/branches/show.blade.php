@extends('layout.user.master')

@section('title', $branch->name . ' Branch - ' . $siteSetting->gym_name)

@section('content')

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" style="background-position: center;" data-setbg="{{ $branch->getFirstMediaUrl('branch_images') ?: asset('assets/user/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb-text">
                    <h2>{{ $branch->name }}</h2>
                    <div class="bt-option">
                        <a href="{{ route('user.home', ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                        <span>{{ $branch->name }} Branch</span>
                    </div>
                    <div class="bh-info mt-3">
                        <span class="badge badge-{{ $branch->type === 'mix' ? 'primary' : ($branch->type === 'ladies' ? 'pink' : 'info') }}">
                            {{ ucfirst($branch->type) }} Branch
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Branch Info Section Begin -->
<section class="branch-info-section spad">
    <div class="container">
        <div class="row">
            <!-- Branch Details -->
            <div class="col-lg-6">
                <div class="branch-details">
                    <h3>About This Branch</h3>
                    <p>{{ $branch->location }}</p>
                    
                    <div class="opening-hours mt-5">
                        <h4 class="text-white">Opening Hours</h4>
                        @if($branch->openingHours->count() > 0)
                            <div class="oh-list">
                                @foreach($branch->openingHours as $hours)
                                    <div class="oh-item">
                                        <span class="day">{{ ucfirst($hours->day_of_week) }}</span>
                                        @if($hours->is_closed)
                                            <span class="time closed">Closed</span>
                                        @else
                                            <span class="time">{{ $hours->opening_time->format('g:i A') }} - {{ $hours->closing_time->format('g:i A') }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-white mt-4">Opening hours not specified</p>
                        @endif
                    </div>

                    @if($branch->phones->count() > 0)
                        <div class="contact-info">
                            <h4>Contact Information</h4>
                            <div class="ci-item">
                                <i class="fa fa-phone"></i>
                                <div class="ci-text">
                                    @foreach($branch->phones as $phone)
                                        <p>{{ $phone->phone_number }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Social Media -->
                    @if($branch->facebook_url || $branch->instagram_url || $branch->x_url)
                        <div class="social-links">
                            <h4>Follow Us</h4>
                            <div class="sl-list">
                                @if($branch->facebook_url)
                                    <a href="{{ $branch->facebook_url }}" target="_blank"><i class="fa fa-facebook"></i></a>
                                @endif
                                @if($branch->instagram_url)
                                    <a href="{{ $branch->instagram_url }}" target="_blank"><i class="fa fa-instagram"></i></a>
                                @endif
                                @if($branch->x_url)
                                    <a href="{{ $branch->x_url }}" target="_blank"><i class="fa fa-twitter"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="map mt-0">
                    @php
                        $embedUrl = $branch->map_url;
                        if (strpos($embedUrl, 'google.com/maps/place/') !== false) {
                            if (preg_match('/@(-?\d+\.?\d*),(-?\d+\.?\d*)/', $embedUrl, $matches)) {
                                $lat = $matches[1];
                                $lng = $matches[2];
                                $embedUrl = "https://maps.google.com/maps?q={$lat},{$lng}&hl=en&z=15&output=embed";
                            }
                        }
                    @endphp
                    <iframe src="{{ $embedUrl }}" loading="lazy" height="550" style="border:0;" allowfullscreen=""></iframe>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Branch Info Section End -->

<!-- Branch Gallery Section Begin -->
@if($branch->galleries->count() > 0)
    <section class="branch-gallery-section">
        <div class="container-fluid">
            <div class="row">
                <div class="gallery-grid">
                    @php
                        $allImages = [];
                        foreach($branch->galleries as $gallery) {
                            foreach($gallery->media as $media) {
                                $allImages[] = $media;
                            }
                        }
                        $firstImage = $allImages[0] ?? null;
                        $remainingImages = array_slice($allImages, 1);
                    @endphp
                    
                    @if($firstImage)
                        <!-- Large Featured Image -->
                        <div class="gallery-large-item">
                            <img src="{{ $firstImage['original_url'] }}" alt="Branch Gallery">
                            <a href="{{ $firstImage['original_url'] }}" class="gi-icon image-popup">
                                <i class="fa fa-picture-o"></i>
                            </a>
                        </div>
                    @endif
                    
                    @if(count($remainingImages) > 0)
                        <!-- Small Images Grid -->
                        <div class="gallery-small-grid">
                            @foreach($remainingImages as $media)
                                <div class="gallery-small-item">
                                    <img src="{{ $media['original_url'] }}" alt="Branch Gallery">
                                    <a href="{{ $media['original_url'] }}" class="gi-icon image-popup">
                                        <i class="fa fa-picture-o"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
<!-- Branch Gallery Section End -->

<!-- Branch Classes Section Begin -->
@if($branch->classes->count() > 0)
    <section class="branch-classes-section pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title mt-5">
                        <span>Classes</span>
                        <h2>AVAILABLE CLASSES</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="classes-slider owl-carousel">
                    
                    @foreach($branch->classes as $class)
                        <div class="class-item">
                            <div class="ci-pic">
                                <img src="{{ $class->getFirstMediaUrl('class_images') }}" alt="{{ $class->name }}">
                            </div>
                            <div class="ci-text">
                                <span>{{ $class->type }}</span>
                                <h5>{{ $class->name }}</h5>
                                <a href="{{ route('user.classes.show', ['siteSetting' => $siteSetting->slug, 'class' => $class->id]) }}"><i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
<!-- Branch Classes Section End -->

<!-- Branch Services Section Begin -->
@if($branch->services->count() > 0)
    <section class="branch-services-section pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Services</span>
                        <h2>AVAILABLE SERVICES</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($branch->services as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-item">
                            <div class="si-pic">
                                <img src="{{ $service->getFirstMediaUrl('service_image') }}" alt="{{ $service->name }}">
                            </div>
                            <div class="si-text">
                                <h4>{{ $service->name }}</h4>
                                <p>{{ Str::limit($service->description, 100) }}</p>
                                <a href="{{ route('user.services.show', ['siteSetting' => $siteSetting->slug, 'service' => $service->id]) }}" class="primary-btn">Learn More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
<!-- Branch Services Section End -->

<!-- Branch Trainers Section Begin -->
@if($branch->trainers->count() > 0)
    <section class="branch-trainers-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Trainers</span>
                        <h2>OUR TRAINERS</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="trainers-slider owl-carousel">
                    @foreach($branch->trainers as $trainer)
                        <div class="trainer-item">
                            <div class="ti-pic">
                                <img src="{{ $trainer->getFirstMediaUrl('profile_photos') ?: asset('assets/user/img/team/team-1.jpg') }}" alt="{{ $trainer->name }}">
                            </div>
                            <div class="ti-text">
                                <h5>{{ $trainer->name }}</h5>
                                <span>Personal Trainer</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
<!-- Branch Trainers Section End -->

@endsection

@section('css')
<style>
/* Breadcrumb section overlay */
.breadcrumb-section {
    position: relative;
}

.breadcrumb-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 1;
}

.breadcrumb-text {
    position: relative;
    z-index: 2;
}

.breadcrumb-text h2 {
    color: white !important;
}

.breadcrumb-text .bt-option a {
    color: white !important;
}

.breadcrumb-text .bt-option span {
    color: #ccc !important;
}

.bh-info .badge {
    color: white !important;
}

/* Section backgrounds */
.branch-info-section,
.branch-gallery-section,
.branch-map-section,
.branch-classes-section,
.branch-services-section,
.branch-trainers-section {
    background: #151515;
}

.branch-details h3 {
    color: white;
    margin-bottom: 1rem;
}

.branch-details p {
    color: #ccc;
    margin-bottom: 1rem;
}

.branch-item {
    background: transparent;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease;
    margin-bottom: 2rem;
}

.branch-item:hover {
    transform: translateY(-5px);
}

.bi-pic img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.bi-text {
    padding: 1.5rem;
}

.bi-text h4 {
    margin-bottom: 0.5rem;
    color: white;
}

.bi-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 1rem 0;
}

.badge-pink {
    background-color: #e91e63;
    color: white;
}

.opening-hours, .social-links {
    background: transparent;
    border-radius: 10px;
    margin-bottom: 2rem;
    width: 80%;
}

.oh-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #444;
    color: white;
}

.oh-item:last-child {
    border-bottom: none;
}

.oh-item .day {
    color: white;
}

.oh-item .time {
    color: #ccc;
}

.time.closed {
    color: #e74c3c;
}

.sl-list a {
    display: inline-block;
    width: 40px;
    height: 40px;
    background: #333;
    color: white;
    text-align: center;
    line-height: 40px;
    border-radius: 50%;
    margin-right: 0.5rem;
    transition: background 0.3s ease;
}

.sl-list a:hover {
    background: #e91e63;
}

/* Gallery Grid Layout */
.gallery-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 0;
    width: 100%;
    padding: 0;
}

.gallery-large-item {
    position: relative;
    border-radius: 0;
    overflow: hidden;
    height: 500px;
}

.gallery-large-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-large-item:hover img {
    transform: scale(1.05);
}

.gallery-small-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    height: 500px;
}

.gallery-small-item {
    position: relative;
    border-radius: 0;
    overflow: hidden;
}

.gallery-small-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-small-item:hover img {
    transform: scale(1.05);
}

.gi-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    text-align: center;
    line-height: 50px;
    border-radius: 50%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-large-item:hover .gi-icon,
.gallery-small-item:hover .gi-icon {
    opacity: 1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: 1fr;
        gap: 0;
        padding: 0;
    }
    
    .gallery-large-item {
        height: 300px;
    }
    
    .gallery-small-grid {
        height: 300px;
        grid-template-columns: 1fr 1fr;
    }
    
    .gallery-small-item {
        height: 50%;
    }
}

.service-item {
    background: transparent;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease;
    margin-bottom: 2rem;
}

.service-item:hover {
    transform: translateY(-5px);
}

.si-pic img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.si-text {
    padding: 1.5rem;
}

.si-text h4 {
    color: white;
}

.si-text p {
    color: #ccc;
}

.trainer-item {
    text-align: center;
    background: transparent;
    border-radius: 10px;
    padding: 2rem;
    margin: 0 1rem;
}

.ti-pic img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
}

.ti-text h5 {
    color: white;
}

.ti-text span {
    color: #ccc;
}

.contact-info{
    margin-top: 2rem;
}

.contact-info h4 {
    color: white;
}

.contact-info p {
    color: #ccc;
}

.ci-item, .mi-item {
    display: flex;
    align-items: center;
    margin: 1rem 0;
}

.ci-item i, .mi-item i {
    font-size: 1.5rem;
    color: #e91e63;
    margin-right: 1rem;
}

.mi-pic img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 1rem;
}

.mi-text h5 {
    color: white;
}

.mi-text p {
    color: #ccc;
}
</style>
@endsection

@section('Js')
    <script>
    $(document).ready(function() {
        // Initialize image popup
        $('.image-popup').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            }
        });

         // Gallery slider removed - using grid layout instead

        $('.classes-slider').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                1200: {
                    items: 3
                }
            }
        });

        $('.trainers-slider').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                1200: {
                    items: 4
                }
            }
        });
    });
    </script>
@endsection
