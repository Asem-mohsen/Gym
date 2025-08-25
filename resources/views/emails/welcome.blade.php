@extends('emails.layout.email')

@section('title', 'Welcome to ' . $gymName)

@section('content')
    <td class="content-cell">
        <div class="f-fallback">
            @if($gymLogo)
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="{{ $gymLogo }}" alt="{{ $gymName }}" style="max-width: 200px; height: auto;">
                </div>
            @endif

            <h1>Welcome to {{ $gymName }}, {{ $userName }}!</h1>
            
            <p>We're thrilled to have you join our fitness community! You've just taken the first step towards achieving your health and fitness goals.</p>

            <div style="padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h2 style="margin-top: 0; color: #333;">About {{ $gymName }}</h2>
                <p style="margin-bottom: 10px;"><strong>Location:</strong> {{ $gymAddress }}</p>
                @if($gymDescription)
                    <p style="margin-bottom: 0;"><strong>Description:</strong> {{ $gymDescription }}</p>
                @endif
            </div>

            <h2>What's Next?</h2>
            <ul>
                <li>Explore our state-of-the-art facilities</li>
                <li>Book your first training session</li>
                <li>Join our group classes</li>
                <li>Connect with our fitness community</li>
            </ul>

            <p>If you have any questions or need assistance getting started, don't hesitate to reach out to us at <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>.</p>

            <p>We're here to support you on your fitness journey!</p>

            <!-- Sub copy -->
            <table class="body-sub" role="presentation">
                <tr>
                    <td>
                        <p class="f-fallback sub">
                            Best regards, <br>
                            The {{ $gymName }} Team
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </td>
@endsection
