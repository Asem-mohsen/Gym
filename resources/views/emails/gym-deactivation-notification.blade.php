@extends('emails.layout.email')

@section('title', 'Important Notice: ' . $gymName . ' Gym Deactivation')

@section('content')
    <td class="content-cell">
        <div class="f-fallback">
            @if($gymLogo)
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="{{ $gymLogo }}" alt="{{ $gymName }}" style="max-width: 200px; height: auto;">
                </div>
            @endif

            <h1>Important Notice: Gym Deactivation</h1>
            
            <p>Dear {{ $userName }},</p>

            <p>We hope this message finds you well. We wanted to personally inform you about an important change regarding your gym membership.</p>

            <div style="padding: 20px; border-radius: 8px; margin: 20px 0; background-color: #f8f9fa; border-left: 4px solid #dc3545;">
                <h2 style="margin-top: 0; color: #333;">{{ $gymName }} Gym Deactivation</h2>
                <p style="margin-bottom: 10px;"><strong>Location:</strong> {{ $gymAddress }}</p>
                <p style="margin-bottom: 0; color: #dc3545;"><strong>Status:</strong> Gym has been deactivated</p>
            </div>

            <h2>What This Means for You</h2>
            <ul>
                <li>You will no longer be able to access {{ $gymName }} through our platform</li>
                <li>Your membership and subscription data has been preserved for your records</li>
                <li>All your personal data and fitness information remains secure</li>
                <li>You can still access other gyms on our platform if you have multiple memberships</li>
            </ul>

            <h2>Your Next Steps</h2>
            <p>We understand this may be disappointing news. Here are some options to consider:</p>
            <ul>
                <li>Explore other gyms available on our platform</li>
                <li>Contact us for recommendations on nearby fitness facilities</li>
                <li>Consider joining a different gym through our network</li>
            </ul>

            <div style="padding: 20px; border-radius: 8px; margin: 20px 0; background-color: #e7f3ff; border-left: 4px solid #007bff;">
                <h3 style="margin-top: 0; color: #007bff;">We Hope to See You Back!</h3>
                <p style="margin-bottom: 0;">We value your membership and hope to welcome you back to our fitness community in the future. Our platform continues to grow with new gyms and facilities being added regularly.</p>
            </div>

            <p>If you have any questions or need assistance finding a new gym, please don't hesitate to reach out to us at <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>.</p>

            <p>Thank you for being part of our fitness community. We wish you continued success in your fitness journey!</p>

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
