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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #f59e0b;
            --dark-bg: #0f0f23;
            --darker-bg: #0a0a1a;
            --card-bg: #1a1a2e;
            --card-hover: #252542;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --text-muted: #6b7280;
            --border-color: #2d2d44;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
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
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--card-bg) 100%);
            padding: 100px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%232d2d44" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 3rem;
        }
        
        .search-container {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }
        
        .search-box {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .search-box:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            margin-top: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .location-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
            color: white;
        }
        
        .gym-grid {
            padding: 80px 0;
            background: var(--dark-bg);
        }
        
        .gym-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
            height: 100%;
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
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .gym-card:hover {
            transform: translateY(-8px);
            background: var(--card-hover);
            border-color: var(--primary-color);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .gym-card:hover::before {
            transform: scaleX(1);
        }
        
        .gym-logo {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            object-fit: cover;
            margin-bottom: 1.5rem;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .gym-card:hover .gym-logo {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }
        
        .gym-logo-placeholder {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .gym-card:hover .gym-logo-placeholder {
            border-color: var(--primary-color);
            transform: scale(1.05);
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
            margin-bottom: 1.5rem;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        
        .feature-icon {
            color: var(--primary-color);
            margin-right: 0.75rem;
            width: 16px;
            text-align: center;
        }
        
        .score-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        
        .score-excellent {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success-color);
        }
        
        .score-very-good {
            background: rgba(99, 102, 241, 0.2);
            color: var(--primary-color);
        }
        
        .score-good {
            background: rgba(139, 92, 246, 0.2);
            color: var(--secondary-color);
        }
        
        .score-average {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning-color);
        }
        
        .score-poor {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }
        
        .gym-button {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .gym-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .gym-button:hover::before {
            left: 100%;
        }
        
        .gym-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
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