@extends('layout.intial-screen.master')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section" style="background-image: url({{ asset('assets/user/img/hero/gym-selections.png') }});">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Choose Your Perfect Gym</h1>
                <p class="hero-subtitle">Discover amazing fitness facilities near you. Each gym offers unique experiences tailored to your fitness journey.</p>
                
                <!-- Search Box -->
                <div class="search-container">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="gymSearch" placeholder="Search for gyms..." autocomplete="off">
                    </div>
                    
                    <!-- Location Button -->
                    <button class="location-button" id="locationButton">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Use My Location
                    </button>
                </div>
                
                @if(isset($userLocation) && $userLocation)
                    <div class="location-info">
                        <i class="fas fa-location-dot"></i>
                        Showing gyms near {{ $userLocation['city'] ?? $userLocation['region'] ?? 'your location' }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Gym Grid Section -->
    <section class="gym-grid">
        <div class="container">
            <!-- Search Results Counter -->
            <div class="search-results" id="searchResults" style="display: none;">
                <span id="resultsCount"></span> gyms found
            </div>
            
            <!-- Loading Animation -->
            <div class="loading" id="loading">
                <div class="loading-spinner"></div>
                <p>Searching for gyms...</p>
            </div>
            
            @if($gyms->count() > 0)
                <div class="row" id="gymGrid">
                    @foreach($gyms as $gym)
                        <div class="col-lg-4 col-md-6 mb-4 gym-item" data-gym-name="{{ strtolower($gym->getTranslation('gym_name', app()->getLocale())) }}">
                            <div class="gym-card">
                                <div class="gym-card-content">
                                    <div class="gym-card-header">
                                        @if($gym->getFirstMediaUrl('gym_logo'))
                                            <img src="{{ $gym->getFirstMediaUrl('gym_logo') }}" alt="{{ $gym->getTranslation('gym_name', app()->getLocale()) }}" class="gym-logo">
                                        @else
                                            <div class="gym-logo-placeholder">
                                                <i class="fas fa-dumbbell"></i>
                                            </div>
                                        @endif
                                        
                                        <h3 class="gym-name">{{ $gym->getTranslation('gym_name', app()->getLocale()) }}</h3>
                                        
                                        @if($gym->getTranslation('description', app()->getLocale()))
                                            <p class="gym-description">{{ Str::limit($gym->getTranslation('description', app()->getLocale()), 120) }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="gym-card-body">
                                        <div class="gym-features">
                                            @if($gym->branches->count() > 0)
                                                <div class="feature-item">
                                                    <i class="fas fa-map-marker-alt feature-icon"></i>
                                                    <span>{{ $gym->branches->count() }} {{ Str::plural('branch', $gym->branches->count()) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($gym->services->count() > 0)
                                                <div class="feature-item">
                                                    <i class="fas fa-dumbbell feature-icon"></i>
                                                    <span>{{ $gym->services->count() }} {{ Str::plural('service', $gym->services->count()) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($gym->classes->count() > 0)
                                                <div class="feature-item">
                                                    <i class="fas fa-calendar-alt feature-icon"></i>
                                                    <span>{{ $gym->classes->count() }} {{ Str::plural('class', $gym->classes->count()) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if(isset($gym->distance_info) && $gym->distance_info)
                                                <div class="feature-item distance-item">
                                                    <i class="fas fa-route feature-icon"></i>
                                                    <span class="distance-text">
                                                        {{ $gym->distance_info['formatted_distance'] }} away
                                                        @if($gym->distance_info['is_nearby'])
                                                            <span class="nearby-badge">Nearby</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            @elseif(isset($userLocation) && $userLocation)
                                                <div class="feature-item distance-item">
                                                    <i class="fas fa-route feature-icon"></i>
                                                    <span class="distance-text">In your area</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="gym-card-footer">
                                        <a href="{{ route('user.home', $gym->slug) }}" class="gym-button">
                                            <i class="fas fa-arrow-right me-2"></i>
                                            Explore Gym
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-gyms">
                    <i class="fas fa-dumbbell"></i>
                    <h3>No Gyms Available</h3>
                    <p>We're working on bringing amazing fitness facilities to your area. Check back soon!</p>
                </div>
            @endif
        </div>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('gymSearch');
            const locationButton = document.getElementById('locationButton');
            const gymItems = document.querySelectorAll('.gym-item');
            const searchResults = document.getElementById('searchResults');
            const resultsCount = document.getElementById('resultsCount');
            const loading = document.getElementById('loading');
            
            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;
                
                gymItems.forEach(item => {
                    const gymName = item.getAttribute('data-gym-name');
                    const isVisible = gymName.includes(searchTerm);
                    
                    item.style.display = isVisible ? 'block' : 'none';
                    if (isVisible) visibleCount++;
                });
                
                // Update results counter
                if (searchTerm) {
                    searchResults.style.display = 'block';
                    resultsCount.textContent = visibleCount;
                } else {
                    searchResults.style.display = 'none';
                }
            });
            
            // Location button functionality
            locationButton.addEventListener('click', function() {
                if (navigator.geolocation) {
                    loading.style.display = 'block';
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Getting Location...';
                    
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Redirect with location parameters
                            const url = new URL(window.location);
                            url.searchParams.set('latitude', lat);
                            url.searchParams.set('longitude', lng);
                            window.location.href = url.toString();
                        },
                        function(error) {
                            loading.style.display = 'none';
                            locationButton.disabled = false;
                            locationButton.innerHTML = '<i class="fas fa-map-marker-alt me-2"></i>Use My Location';
                            
                            let message = 'Unable to get your location. ';
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    message += 'Please allow location access.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    message += 'Location information unavailable.';
                                    break;
                                case error.TIMEOUT:
                                    message += 'Location request timed out.';
                                    break;
                                default:
                                    message += 'An unknown error occurred.';
                                    break;
                            }
                            
                            alert(message);
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 300000
                        }
                    );
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });
            
            // Add smooth scroll animation to gym cards
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            gymItems.forEach(item => {
                observer.observe(item);
            });
        });
    </script>
    
@endsection
