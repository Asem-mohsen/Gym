@extends('emails.layout.email')

@section('title','Password Reset')

@section('content')

    <td class="content-cell">
        <div class="f-fallback">
            
            <h1>Hi, {{ $user->name }}!</h1>

            <p>We received a request to reset your password. Find below the code to reset your password:</p>

            <p>
                <a href="{{ $resetUrl }}">Reset Password</a>
            </p>

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
