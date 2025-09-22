<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Successful - {{ $gym->gym_name ?? 'Gym' }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .success-icon {
            font-size: 60px;
            color: #10b981;
            margin-bottom: 20px;
        }
        .message {
            font-size: 18px;
            color: #374151;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .user-info {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .user-name {
            font-weight: 600;
            color: #111827;
            font-size: 16px;
        }
        .gym-name {
            color: #6b7280;
            font-size: 14px;
            margin-top: 5px;
        }
        .timestamp {
            color: #6b7280;
            font-size: 12px;
            margin-top: 20px;
        }
        .close-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .close-btn:hover {
            background: #5a67d8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✅</div>
        <div class="message">{{ $message }}</div>
        
        <div class="user-info">
            <div class="user-name">{{ $user->name }}</div>
            <div class="gym-name">{{ $gym->gym_name ?? 'Gym' }}</div>
        </div>
        
        <div class="timestamp">
            Check-in time: {{ $checkin->created_at->format('M j, Y \a\t g:i A') }}
        </div>
        
        <button class="close-btn" onclick="window.close()">Close</button>
    </div>
</body>
</html>
