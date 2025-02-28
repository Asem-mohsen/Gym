@extends('layout.email')

@section('title','Password Reset')

@section('content')

    <td class="content-cell">
        <div class="f-fallback">
            
            <h1>Hi, {{ $userName }}!</h1>

            <p>We received a request to reset your password for your {{ $appName }} account. Find below the code to reset your password:</p>

            <h2 style="text-align: center; font-size: 24px;">{{ $code }}</h2>

            <p>Enter this code in the reset passwword form.</p>

            <p>If you didn’t request a password reset, please ignore this email or contact us immediately at <a href="mailto:{{ $supportMail }}">{{ $supportMail }}</a>.</p>

            <p>Thank you for choosing {{ $appName }}!</p>

            <!-- Sub copy -->
            <table class="body-sub" role="presentation">
                <tr>
                    <td>
                        <p class="f-fallback sub">
                            Best regards, <br>
                            {{ $appName }} Team
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </td>
@endsection
