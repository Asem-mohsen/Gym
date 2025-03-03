@extends('layout.email')

@section('title','Invitation Accepted')

@section('content')

    <td class="content-cell">
        <div class="f-fallback">
            <h1>Hi, {{ $managerName }}!</h1>
            <p>We’re excited to let you know that {{ $userName }} has accepted your invitation to join {{ $appName }} and is now an active user.</p>

            <p>You can now collaborate with {{ $userName }} within {{ $appName }} and start working together.</p>

            <p>If you need any further assistance or have any questions, feel free to reach out to us at <a href="mailto:{{ $supportMail }}">{{ $supportMail }}</a></p>

            <p>Thank you for choosing {{ $appName }} .</p>

            <!-- Sub copy -->
            <table class="body-sub" role="presentation">
                <tr>
                    <td>
                        <p class="f-fallback sub">
                            Best regards, <br>
                            The {{ $appName }} Team
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </td>
@endsection
