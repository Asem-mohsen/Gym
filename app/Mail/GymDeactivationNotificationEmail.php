<?php

namespace App\Mail;

use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GymDeactivationNotificationEmail extends Mailable implements ShouldQueue
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
            subject: 'Important Notice: ' . $this->gym->gym_name . ' Gym Deactivation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.gym-deactivation-notification',
            with: [
                'userName' => $this->user->name,
                'gymName' => $this->gym->gym_name,
                'gymAddress' => $this->gym->address,
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
