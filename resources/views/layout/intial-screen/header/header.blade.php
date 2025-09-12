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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/toastr.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6b7280;
            --secondary-color: #9ca3af;
            --accent-color: #ef4444;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            
            --gradient-primary: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            --gradient-secondary: linear-gradient(135deg, #4b5563 0%, #6b7280 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            --gradient-danger: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            --gradient-info: linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%);
            
            --dark-bg: #111827;
            --darker-bg: #0f172a;
            --card-bg: rgba(31, 41, 55, 0.8);
            --card-hover: rgba(31, 41, 55, 0.95);
            --glass-bg: rgba(31, 41, 55, 0.6);
            --glass-border: rgba(75, 85, 99, 0.3);
            
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --border-color: rgba(75, 85, 99, 0.4);
            
            --shadow-primary: 0 20px 40px rgba(17, 24, 39, 0.4);
            --shadow-secondary: 0 20px 40px rgba(31, 41, 55, 0.4);
            --shadow-success: 0 20px 40px rgba(16, 185, 129, 0.3);
            --shadow-warning: 0 20px 40px rgba(245, 158, 11, 0.3);
            --shadow-danger: 0 20px 40px rgba(239, 68, 68, 0.3);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark-bg);
            min-height: 100vh;
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        .hero-section {
            background: var(--gradient-primary);
            padding: 120px 0 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(55, 65, 81, 0.4) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(75, 85, 99, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(107, 114, 128, 0.2) 0%, transparent 50%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 2rem;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: slideInUp 1s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.4rem;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.9);
            max-width: 700px;
            margin: 0 auto 3rem;
            line-height: 1.8;
            animation: slideInUp 1s ease-out 0.2s both;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
            animation: slideInUp 1s ease-out 0.4s both;
        }
        
        .search-box {
            background: var(--glass-bg);
            border: 2px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.2rem 1.8rem;
            display: flex;
            align-items: center;
            transition: all 0.4s ease;
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .search-box:focus-within {
            border-color: rgba(75, 85, 99, 0.6);
            box-shadow: 0 0 0 4px rgba(75, 85, 99, 0.2), 0 12px 40px rgba(0, 0, 0, 0.3);
            transform: translateY(-4px);
        }
        
        .search-input {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
            font-size: 1.1rem;
            color: var(--text-primary);
            margin-left: 1rem;
        }
        
        .search-input::placeholder {
            color: var(--text-muted);
        }
        
        .search-icon {
            color: var(--text-muted);
            font-size: 1.1rem;
        }
        
        .location-button {
            background: var(--gradient-secondary);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 16px;
            font-weight: 600;
            margin-top: 1.5rem;
            transition: all 0.4s ease;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(75, 85, 99, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .location-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .location-button:hover::before {
            left: 100%;
        }
        
        .location-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(75, 85, 99, 0.4);
            color: white;
        }
        
        .location-info {
            margin-top: 2rem;
            padding: 1rem 2rem;
            background: rgba(31, 41, 55, 0.6);
            border-radius: 16px;
            border: 1px solid rgba(75, 85, 99, 0.3);
            color: var(--text-secondary);
            font-size: 1.1rem;
            display: inline-block;
            backdrop-filter: blur(10px);
        }
        
        .location-info i {
            color: var(--success-color);
            margin-right: 0.5rem;
        }
        
        .gym-grid {
            padding: 100px 0;
            background: var(--dark-bg);
            position: relative;
        }
        
        .gym-grid::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 10% 20%, rgba(55, 65, 81, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(75, 85, 99, 0.15) 0%, transparent 50%);
        }
        
        .gym-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            transition: all 0.4s ease;
            height: 100%;
            width: 100%;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(40px);
            animation: fadeInUp 0.8s ease forwards;
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .gym-card-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 2.5rem;
        }
        
        .gym-card-header {
            text-align: center;
            flex-shrink: 0;
        }
        
        .gym-card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin: 1rem 0;
        }
        
        .gym-card-footer {
            flex-shrink: 0;
            text-align: center;
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
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .gym-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(55, 65, 81, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        
        .gym-card:hover {
            transform: translateY(-12px) scale(1.02);
            background: var(--card-hover);
            border-color: rgba(75, 85, 99, 0.6);
            box-shadow: var(--shadow-primary);
        }
        
        .gym-card:hover::before {
            transform: scaleX(1);
        }
        
        .gym-card:hover::after {
            opacity: 1;
        }
        
        .gym-logo {
            width: 90px;
            height: 90px;
            border-radius: 20px;
            object-fit: cover;
            margin-bottom: 1.5rem;
            border: 3px solid var(--glass-border);
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .gym-card:hover .gym-logo {
            border-color: rgba(75, 85, 99, 0.8);
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }
        
        .gym-logo-placeholder {
            background: var(--gradient-primary);
            color: var(--text-primary);
            font-size: 2.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 90px;
            height: 90px;
            border-radius: 20px;
            margin-bottom: 1.5rem;
            border: 3px solid var(--glass-border);
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .gym-card:hover .gym-logo-placeholder {
            border-color: rgba(75, 85, 99, 0.8);
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        }
        
        .gym-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .gym-description {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .gym-features {
            margin-bottom: 0;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
            flex-wrap: wrap;
        }
        
        .feature-icon {
            color: var(--primary-color);
            margin-right: 0.75rem;
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }
        
        .location-text {
            display: block;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            font-style: italic;
        }
        
        .distance-item {
            background: rgba(56, 161, 105, 0.1);
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }
        
        .distance-text {
            color: var(--success-color);
            font-weight: 600;
        }
        
        .nearby-badge {
            background: var(--gradient-success);
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(56, 161, 105, 0.3);
        }
        
        .score-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-left: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .score-excellent {
            background: var(--gradient-success);
            color: white;
            box-shadow: 0 2px 10px rgba(56, 161, 105, 0.3);
        }
        
        .score-very-good {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 2px 10px rgba(55, 65, 81, 0.3);
        }
        
        .score-good {
            background: var(--gradient-secondary);
            color: white;
            box-shadow: 0 2px 10px rgba(75, 85, 99, 0.3);
        }
        
        .score-average {
            background: var(--gradient-warning);
            color: white;
            box-shadow: 0 2px 10px rgba(214, 158, 46, 0.3);
        }
        
        .score-poor {
            background: var(--gradient-danger);
            color: white;
            box-shadow: 0 2px 10px rgba(229, 62, 62, 0.3);
        }
        
        .gym-button {
            background: var(--gradient-success);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 16px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.4s ease;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
            font-size: 1.1rem;
        }
        
        .gym-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }
        
        .gym-button:hover::before {
            left: 100%;
        }
        
        .gym-button:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-success);
            color: white;
        }
        
        .search-results {
            margin-bottom: 2rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 1.1rem;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border-color);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .no-gyms {
            text-align: center;
            padding: 4rem 0;
            color: var(--text-secondary);
        }
        
        .no-gyms i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .location-info {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        .location-info i {
            color: var(--primary-color);
            margin-right: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-section {
                padding: 80px 0 60px;
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