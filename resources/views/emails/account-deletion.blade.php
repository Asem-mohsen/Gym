@extends('emails.layout.email')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        @if($gymLogo)
            <img src="{{ $gymLogo }}" alt="{{ $gymName }}" style="max-width: 200px; height: auto;">
        @endif
    </div>

    <h1>We'll Miss You, {{ $userName }}!</h1>
    
    <p>It's with a heavy heart that we say goodbye to you at {{ $gymName }}. We've truly enjoyed having you as part of our fitness community.</p>

    <div style="padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h2 style="margin-top: 0; color: #333;">Your Journey With Us</h2>
        <p style="margin-bottom: 10px;">We want to thank you for choosing {{ $gymName }} for your fitness journey. Your dedication and commitment have been inspiring to us and our community.</p>
        
        <p style="margin-bottom: 10px;"><strong>Location:</strong> {{ $gymAddress }}</p>
    </div>

    <div style="padding: 15px; border-radius: 8px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #1976d2;">We're Here If You Need Us</h3>
        <p style="margin-bottom: 10px;">If you ever change your mind or face any issues, please don't hesitate to reach out to us:</p>
        <p style="margin-bottom: 0;"><strong>Email:</strong> <a href="mailto:{{ $contactEmail }}" style="color: #1976d2;">{{ $contactEmail }}</a></p>
    </div>

    <div style="padding: 15px; border-radius: 8px; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #28a745;">Come Back Anytime!</h3>
        <p style="margin-bottom: 0;">Our doors are always open for you. If you decide to return to your fitness journey, we'll be here to welcome you back with open arms.</p>
    </div>

    <p style="text-align: center; margin-top: 30px; color: #666;">
        Thank you for being part of the {{ $gymName }} family.<br>
        We hope to see you again soon!
    </p>

    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
        <p style="color: #999; font-size: 14px;">
            Best regards,<br>
            The {{ $gymName }} Team
        </p>
    </div>
</div>
@endsection
