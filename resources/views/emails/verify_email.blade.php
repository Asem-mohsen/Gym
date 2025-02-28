@extends('layout.email')

@section('title','Email Verification')

@section('content')

    <td class="content-cell">
        <div class="f-fallback">
            <h1>Hi, {{ $userName }}!</h1>
            <p>Thank you for registering with {{ $appName }}. To complete your registration, please verify your email address by clicking the link below:</p>

            <p style="text-align: center;">
                <a href="{{ $verificationLink }}" class="button" style="color: white; background-color: #3490dc; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Verify Email</a>
            </p>

            <p>If you encountered any issues, please reach out to us at <a href="mailto:{{ $supportMail }}">{{ $supportMail }}</a>.</p>

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
