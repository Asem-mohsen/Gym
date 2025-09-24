<?php

namespace App\Mail;

use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountDeletionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public SiteSetting $gym;

    public function __construct(User $user, SiteSetting $gym)
    {
        $this->user = $user;
        $this->gym = $gym;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We\'ll Miss You at ' . $this->gym->getTranslation('gym_name', 'en'),
            from: new Address(
                $this->gym->contact_email ?: config('mail.from.address'),
                $this->gym->getTranslation('gym_name', 'en')
            ),
            replyTo: $this->gym->contact_email ? 
                [new Address($this->gym->contact_email, $this->gym->getTranslation('gym_name', 'en'))] : 
                [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-deletion',
            with: [
                'userName' => $this->user->name,
                'gymName' => $this->gym->getTranslation('gym_name', 'en'),
                'gymAddress' => $this->gym->getTranslation('address', 'en'),
                'contactEmail' => $this->gym->contact_email,
                'gymLogo' => $this->gym->getFirstMediaUrl('email_logo') ?: $this->gym->getFirstMediaUrl('gym_logo'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
