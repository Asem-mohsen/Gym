<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteSetting;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;
    public ?SiteSetting $gym;

    public function __construct($token, $user, ?SiteSetting $gym = null)
    {
        $this->token = $token;
        $this->user = $user;
        $this->gym = $gym;
    }

    public function envelope(): Envelope
    {
        $subject = $this->gym ? 
            'Reset Your Password - ' . $this->gym->gym_name : 
            'Reset Your Password';
            
        return new Envelope(
            subject: $subject,
            from: new Address(
                ($this->gym && $this->gym->contact_email) ? 
                    $this->gym->contact_email : 
                    config('mail.from.address'),
                $this->gym ? $this->gym->gym_name : config('mail.from.name')
            ),
            replyTo: ($this->gym && $this->gym->contact_email) ? 
                [new Address($this->gym->contact_email, $this->gym->gym_name)] : 
                [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'token' => $this->token,
                'user' => $this->user,
                'resetUrl' => url('/auth/forget-password/reset?token=' . $this->token . '&email=' . $this->user->email),
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
