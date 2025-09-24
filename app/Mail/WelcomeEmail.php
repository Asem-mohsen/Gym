<?php

namespace App\Mail;

use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
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
            subject: 'Welcome to ' . $this->gym->gym_name,
            from: new Address(
                $this->gym->contact_email ?: config('mail.from.address'),
                $this->gym->gym_name
            ),
            replyTo: $this->gym->contact_email ? 
                [new Address($this->gym->contact_email, $this->gym->gym_name)] : 
                [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'userName' => $this->user->name,
                'gymName' => $this->gym->gym_name,
                'gymAddress' => $this->gym->address,
                'gymDescription' => $this->gym->description,
                'gymLogo' => $this->gym->getFirstMediaUrl('email_logo') ?: $this->gym->getFirstMediaUrl('gym_logo'),
                'contactEmail' => $this->gym->contact_email,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
