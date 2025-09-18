@extends('emails.layout.email')

@section('title', 'You\'re Invited to Join ' . $gym->gym_name)

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #333; margin-bottom: 10px;">You're Invited!</h1>
        <p style="color: #666; font-size: 18px;">{{ $inviter->name }} has invited you to join {{ $gym->gym_name }}</p>
    </div>

    <div style="padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h2 style="color: #333; margin-bottom: 15px;">Invitation Details</h2>
        <div style="margin-bottom: 10px;">
            <strong>From:</strong> {{ $inviter->name }}
        </div>
        <div style="margin-bottom: 10px;">
            <strong>Gym:</strong> {{ $gym->gym_name }}
        </div>
        <div style="margin-bottom: 10px;">
            <strong>Expires:</strong> {{ $invitation->expires_at->format('F j, Y') }}
        </div>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <h3 style="color: #333; margin-bottom: 15px;">Your QR Code</h3>
        <div style="padding: 20px; display: inline-block;">
            {!! $qrCodeImage !!}
        </div>
        <p style="color: #666; margin-top: 15px; font-size: 14px;">
            Show this QR code at the gym to redeem your invitation
        </p>
    </div>

    <div style="padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h3 style="color: #1976d2; margin-bottom: 15px;">How to Use Your Invitation</h3>
        <ol style="color: #333; line-height: 1.6;">
            <li>Visit {{ $gym->name }} during business hours</li>
            <li>Show this QR code to the staff at the front desk</li>
            <li>The staff will scan the QR code to verify your invitation</li>
        </ol>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <h3 style="color: #333; margin-bottom: 15px;">Important Notes</h3>
        <ul style="color: #666; text-align: left; display: inline-block;">
            <li>This invitation is valid for 30 days from the date of issue</li>
            <li>Each QR code can only be used once</li>
            <li>You must present a valid ID when redeeming the invitation</li>
            <li>This invitation is non-transferable</li>
        </ul>
    </div>

    <div style="text-align: center; padding: 20px; border-radius: 8px;">
        <p style="color: #666; margin-bottom: 10px;">
            <strong>Need Help?</strong>
        </p>
        <p style="color: #666; font-size: 14px;">
            Contact {{ $gym->gym_name }} at {{ $gym->phone ?? 'N/A' }} or email {{ $gym->contact_email ?? 'N/A' }}
        </p>
    </div>

    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
        <p style="color: #999; font-size: 12px;">
            This invitation was sent by {{ $inviter->name }} on {{ $invitation->created_at->format('F j, Y') }}
        </p>
    </div>
</div>
@endsection
