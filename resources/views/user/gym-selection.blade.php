<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Choose from our network of premium fitness facilities. Find the perfect gym for your fitness journey with state-of-the-art equipment and expert trainers.">
    <meta name="keywords" content="gym, fitness, workout, exercise, health, wellness, training">
    <meta name="author" content="Fitness Network">
    
    <title>Choose Your Gym - Fitness Network</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --gray-color: #6b7280;
            --border-color: #e5e7eb;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--dark-color);
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.9) 0%, rgba(139, 92, 246, 0.9) 100%);
            padding: 80px 0;
            text-align: center;
            color: white;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        
        .gym-grid {
            padding: 80px 0;
            background: var(--light-color);
        }
        
        .gym-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s ease forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .gym-item:nth-child(1) .gym-card { animation-delay: 0.1s; }
        .gym-item:nth-child(2) .gym-card { animation-delay: 0.2s; }
        .gym-item:nth-child(3) .gym-card { animation-delay: 0.3s; }
        .gym-item:nth-child(4) .gym-card { animation-delay: 0.4s; }
        .gym-item:nth-child(5) .gym-card { animation-delay: 0.5s; }
        .gym-item:nth-child(6) .gym-card { animation-delay: 0.6s; }
        
        .gym-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .gym-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }
        
        .gym-card:hover::before {
            transform: scaleX(1);
        }
        
        .gym-logo {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            object-fit: cover;
            margin-bottom: 1.5rem;
            border: 3px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .gym-card:hover .gym-logo {
            border-color: var(--primary-color);
            transform: scale(1.1);
        }
        
        .gym-card .gym-logo-placeholder {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 20px;
            margin-bottom: 1.5rem;
            border: 3px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .gym-card:hover .gym-logo-placeholder {
            border-color: var(--primary-color);
            transform: scale(1.1);
        }
        
        .gym-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }
        
        .gym-description {
            color: var(--gray-color);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .gym-features {
            margin-bottom: 1.5rem;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--gray-color);
        }
        
        .feature-icon {
            color: var(--primary-color);
            margin-right: 0.5rem;
            width: 16px;
        }
        
        .gym-button {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }
        
        .gym-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            color: white;
        }
        
        .stats-section {
            background: white;
            padding: 60px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--gray-color);
            font-weight: 500;
        }
        
        .footer {
            background: var(--dark-color);
            color: white;
            text-align: center;
            padding: 2rem 0;
        }
        
        .search-box {
            background: white;
            border-radius: 50px;
            padding: 1rem 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .search-box:focus-within {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }
        
        .search-input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 1.1rem;
            color: var(--dark-color);
        }
        
        .search-input::placeholder {
            color: var(--gray-color);
        }
        
        .search-icon {
            color: var(--gray-color);
            margin-right: 1rem;
            font-size: 1.1rem;
        }
        
        .search-box {
            display: flex;
            align-items: center;
        }
        
        .no-gyms {
            text-align: center;
            padding: 4rem 0;
            color: var(--gray-color);
        }
        
        .no-gyms i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .search-results {
            margin-bottom: 2rem;
            text-align: center;
            color: var(--gray-color);
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-section {
                padding: 60px 0;
            }
            
            .gym-grid {
                padding: 60px 0;
            }
            
            .gym-card {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Choose Your Perfect Gym</h1>
            <p class="hero-subtitle">Discover amazing fitness facilities near you. Each gym offers unique experiences tailored to your fitness journey.</p>
            
            <!-- Search Box -->
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="gymSearch" placeholder="Search for gyms..." autocomplete="off">
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
                                <div class="text-center">
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
                                    
                                    <div class="gym-features">
                                        @if($gym->branches->count() > 0)
                                            <div class="feature-item">
                                                <i class="fas fa-map-marker-alt feature-icon"></i>
                                                {{ $gym->branches->count() }} {{ Str::plural('branch', $gym->branches->count()) }}
                                            </div>
                                        @endif
                                        
                                        @if($gym->services->count() > 0)
                                            <div class="feature-item">
                                                <i class="fas fa-dumbbell feature-icon"></i>
                                                {{ $gym->services->count() }} {{ Str::plural('service', $gym->services->count()) }}
                                            </div>
                                        @endif
                                        
                                        @if($gym->classes->count() > 0)
                                            <div class="feature-item">
                                                <i class="fas fa-calendar-alt feature-icon"></i>
                                                {{ $gym->classes->count() }} {{ Str::plural('class', $gym->classes->count()) }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('user.home', $gym->slug) }}" class="gym-button">
                                        <i class="fas fa-arrow-right me-2"></i>
                                        Explore Gym
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-gyms">
                    <i class="fas fa-gym"></i>
                    <h3>No Gyms Available</h3>
                    <p>We're working on bringing amazing fitness facilities to your area. Check back soon!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Stats Section -->
    @if($gyms->count() > 0)
        <section class="stats-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <div class="stat-number">{{ $gyms->count() }}</div>
                            <div class="stat-label">Total Gyms</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <div class="stat-number">{{ $gyms->sum(function($gym) { return $gym->branches->count(); }) }}</div>
                            <div class="stat-label">Total Branches</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <div class="stat-number">{{ $gyms->sum(function($gym) { return $gym->services->count(); }) }}</div>
                            <div class="stat-label">Total Services</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Fitness Network. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Search Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('gymSearch');
            const gymItems = document.querySelectorAll('.gym-item');
            const searchResults = document.getElementById('searchResults');
            const resultsCount = document.getElementById('resultsCount');
            const loading = document.getElementById('loading');
            
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                // Clear previous timeout
                clearTimeout(searchTimeout);
                
                // Show loading for better UX
                if (searchTerm.length > 0) {
                    loading.style.display = 'block';
                    searchResults.style.display = 'none';
                }
                
                // Add delay to prevent excessive searching
                searchTimeout = setTimeout(() => {
                    let visibleCount = 0;
                    
                    gymItems.forEach(item => {
                        const gymName = item.dataset.gymName;
                        if (gymName.includes(searchTerm)) {
                            item.style.display = 'block';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    // Hide loading
                    loading.style.display = 'none';
                    
                    // Show search results
                    if (searchTerm.length > 0) {
                        searchResults.style.display = 'block';
                        resultsCount.textContent = visibleCount;
                        
                        // Show no results message if needed
                        if (visibleCount === 0) {
                            resultsCount.innerHTML = '<span style="color: #ef4444;">No gyms found</span>';
                        }
                    } else {
                        searchResults.style.display = 'none';
                    }
                }, 300);
            });
            
            // Add smooth scroll animation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Add keyboard navigation
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.dispatchEvent(new Event('input'));
                }
            });
        });
    </script>
</body>
</html>
