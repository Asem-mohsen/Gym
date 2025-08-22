@extends('emails.layout.email')

@section('title','Contact Us')

@section('content')

    <td class="content-cell">
        <td class="content-cell">
            <div class="f-fallback">
            <h1>New Contact Us Message from {{ $userName }}</h1>
            <p><strong>Email:</strong> {{ $userEmail }}</p>
            <p><strong>Subject:</strong> {{ $subject }}</p>
            <p><strong>Message:</strong></p>
            <p>{{ $messageContent }}</p>

            <p>{{ $appName }} Team.</p>

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
