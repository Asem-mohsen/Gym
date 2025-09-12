<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Attachment;
use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'re Invited to Join ' . $this->invitation->gym->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $qrCode = QrCode::format('svg')
            ->size(200)
            ->margin(10)
            ->generate($this->invitation->qr_code_url);

        return new Content(
            view: 'emails.invitation',
            with: [
                'invitation' => $this->invitation,
                'gym' => $this->invitation->gym,
                'inviter' => $this->invitation->inviter,
                'membership' => $this->invitation->membership,
                'qrCodeImage' => $qrCode,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
