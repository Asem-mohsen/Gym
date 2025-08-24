@extends('emails.layout.email')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #333; margin-bottom: 10px;">Welcome to {{ $gymName }}!</h1>
        <p style="color: #666; font-size: 16px;">Your account has been created successfully.</p>
    </div>

    <div style="padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h2 style="color: #333; margin-bottom: 15px;">Account Details</h2>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Phone:</strong> {{ $user->phone }}</p>
    </div>

    <div style="padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h3 style="color: #1976d2; margin-bottom: 15px;">Next Steps</h3>
        <p style="color: #333; margin-bottom: 15px;">
            To complete your account setup, please click the button below to set your password:
        </p>
        <div style="text-align: center;">
            <a href="{{ $resetUrl }}" 
               style="background-color: #1976d2; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                Set Your Password
            </a>
        </div>
        <p style="color: #666; font-size: 14px; margin-top: 15px;">
            This link will expire in 7 days. If you don't see the button, copy and paste this link into your browser:
        </p>
        <p style="color: #1976d2; font-size: 14px; word-break: break-all;">
            {{ $resetUrl }}
        </p>
    </div>

    <div style="padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h3 style="color: #f57c00; margin-bottom: 15px;">Security Notice</h3>
        <ul style="color: #333; padding-left: 20px;">
            <li>Choose a strong password that you haven't used elsewhere</li>
            <li>Your password should be at least 8 characters long</li>
            <li>Include a mix of uppercase, lowercase, numbers, and symbols</li>
            <li>Never share your password with anyone</li>
        </ul>
    </div>

    <div style="text-align: center; color: #666; font-size: 14px;">
        <p>If you didn't expect this email, please ignore it.</p>
        <p>Thank you for choosing {{ $gymName }}!</p>
    </div>
</div>
@endsection
