@extends('emails.layout.email')

@section('title','Admin Onboarding')

@section('content')

    <td class="content-cell">
        <div class="f-fallback">
            
            <h1>Welcome, {{ $user->name }}!</h1>

            <p>You have been added as an administrator to {{ config('app.name') }}. To complete your account setup, please set up your password by clicking the link below:</p>

            <p style="text-align: center;">
                <a href="{{ $setupUrl }}" class="button" style="color: white; background-color: #3490dc; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Set Up Password</a>
            </p>

            <p>This link will expire in 60 minutes for security reasons.</p>

            <p>If you didn't expect this email or have any questions, please contact the system administrator.</p>

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
