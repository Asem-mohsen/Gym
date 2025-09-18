@extends('emails.layout.email')

@section('title','Password Reset')

@section('content')

    <td class="content-cell">
        <div class="f-fallback">
            
            <h1>Hi, {{ $user->name }}!</h1>

            <p>We received a request to reset your password. Use the following code to reset your password:</p>

            <div style="background-color: #f8f9fa; border: 2px solid #dee2e6; border-radius: 8px; padding: 20px; text-align: center; margin: 20px 0;">
                <h2 style="color: #007bff; font-size: 32px; font-weight: bold; letter-spacing: 5px; margin: 0;">{{ $token }}</h2>
            </div>

            <p style="color: #6c757d; font-size: 14px;">This code will expire in {{ config('auth.passwords.users.expire', 60) }} minutes.</p>

            <p>If you didn’t request a password reset, please ignore this email.</p>

            <p>Thank you!</p>

            <!-- Sub copy -->
            <table class="body-sub" role="presentation">
                <tr>
                    <td>
                        <p class="f-fallback sub">
                            Best regards, <br>
                            {{ config('app.name') }} Team
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </td>
@endsection
