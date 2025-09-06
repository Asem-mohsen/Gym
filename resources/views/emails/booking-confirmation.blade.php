@extends('emails.layout.email')

@section('title', 'Booking Confirmation - ' . $bookableType . ' at ' . $gymName)

@section('content')
    <td class="content-cell">
        <div class="f-fallback">
            @if($gymLogo)
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="{{ $gymLogo }}" alt="{{ $gymName }}" style="max-width: 100px; height: auto;">
                </div>
            @endif

            <h1>Booking Confirmation</h1>
            
            <p>Hi {{ $userName }},</p>
            
            <p>Your {{ strtolower($bookableType) }} has been successfully confirmed at {{ $gymName }}!</p>

            <div style="padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h2 style="margin-top: 0; color: #333;">Booking Details</h2>
                <p style="margin-bottom: 10px;"><strong>{{ $bookableType }}:</strong> {{ $bookableName }}</p>
                <p style="margin-bottom: 10px;"><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                <p style="margin-bottom: 10px;"><strong>Booking Date:</strong> {{ $booking->booking_date ? $booking->booking_date->format('F j, Y') : 'N/A' }}</p>
                @if($booking->start_date)
                    <p style="margin-bottom: 10px;"><strong>Start Date:</strong> {{ $booking->start_date->format('F j, Y') }}</p>
                @endif
                @if($booking->end_date)
                    <p style="margin-bottom: 10px;"><strong>End Date:</strong> {{ $booking->end_date->format('F j, Y') }}</p>
                @endif
                @if($payment && $payment->amount)
                    <p style="margin-bottom: 10px;"><strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}</p>
                    <p style="margin-bottom: 0;"><strong>Payment Status:</strong> 
                        <span style="color: {{ $payment->status === 'completed' ? '#28a745' : '#ffc107' }};">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </p>
                @endif
            </div>

            @if($booking->notes)
                <div style="padding: 15px; border-radius: 8px; margin: 20px 0;">
                    <h3 style="margin-top: 0; color: #1976d2;">Additional Notes</h3>
                    <p style="margin-bottom: 0;">{{ $booking->notes }}</p>
                </div>
            @endif

            <h2>What's Next?</h2>
            <ul>
                <li>Arrive at least 10 minutes before your scheduled time</li>
                <li>Check in with our staff upon arrival</li>
                <li>Enjoy your {{ strtolower($bookableType) }}!</li>
            </ul>

            <p>If you need to make any changes to your booking or have questions, please contact us at <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>.</p>

            <p>Thank you for choosing {{ $gymName }}!</p>

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
