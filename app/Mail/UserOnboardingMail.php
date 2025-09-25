<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Attachment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserOnboardingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;
    public $gymName;
    public $gymContactEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $gymName, ?string $gymContactEmail = null)
    {
        $this->user = $user;
        $this->gymName = $gymName;
        $this->gymContactEmail = $gymContactEmail;
        
        $token = Str::random(64);
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );
        
        // Get the first gym associated with the user for the reset URL
        $userGym = $user->gyms()->first();
        $gymSlug = $userGym ? $userGym->slug : null;
        
        if ($gymSlug) {
            // Use the new route with gym context
            $this->resetUrl = URL::temporarySignedRoute(
                'auth.forget-password.reset-form',
                now()->addDays(7),
                ['siteSetting' => $gymSlug, 'token' => $token, 'email' => $user->email]
            );
        } else {
            // Fallback to the old route without gym context
            $this->resetUrl = URL::temporarySignedRoute(
                'auth.forget-password.reset-form',
                now()->addDays(7),
                ['token' => $token, 'email' => $user->email]
            );
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . $this->gymName . ' - Set Your Password',
            from: new Address(
                $this->gymContactEmail ?: config('mail.from.address'),
                $this->gymName
            ),
            replyTo: $this->gymContactEmail ? 
                [new Address($this->gymContactEmail, $this->gymName)] : 
                [],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.user-onboarding',
            with: [
                'user' => $this->user,
                'resetUrl' => $this->resetUrl,
                'gymName' => $this->gymName,
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
