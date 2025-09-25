<?php

namespace App\Mail;

use Throwable;
use Illuminate\Mail\Mailables\Attachment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AdminOnboardingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;
    public $gymName;
    public $gymSlug;
    public $token;
    public $gymContactEmail;
    
    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 60;
    
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $gymName, string $gymSlug, string $token, ?string $gymContactEmail = null)
    {
        $this->user = $user;
        $this->gymName = $gymName;
        $this->gymSlug = $gymSlug;
        $this->token = $token;
        $this->gymContactEmail = $gymContactEmail;

        $this->resetUrl = URL::temporarySignedRoute(
            'auth.admin-setup-password',
            now()->addDays(7),
            ['siteSetting' => $gymSlug, 'token' => $token, 'email' => $user->email]
        );

        Log::info('Reset URL: ' . $this->resetUrl);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . $this->gymName . ' - Admin Account Setup',
            from: new Address(
                $this->gymContactEmail ?: config('mail.from.address'),
                $this->gymName
            ),
            replyTo: $this->gymContactEmail ? 
                [new Address($this->gymContactEmail, $this->gymName)] : 
                [],
            tags: ['admin-onboarding', 'user:' . $this->user->id, 'gym:' . $this->gymSlug],
            metadata: [
                'user_id' => $this->user->id,
                'gym_slug' => $this->gymSlug,
                'token' => $this->token,
                'email' => $this->user->email,
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $content = new Content(
            view: 'emails.admin-onboarding',
            with: [
                'user' => $this->user,
                'resetUrl' => $this->resetUrl,
                'gymName' => $this->gymName,
            ],
        );
            
        return $content;
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

    public function failed(Throwable $exception): void
    {
        Log::error('AdminOnboardingMail: Job failed', [
            'user_id' => $this->user->id ?? 'unknown',
            'user_email' => $this->user->email ?? 'unknown',
            'gym_name' => $this->gymName ?? 'unknown',
            'reset_url' => $this->resetUrl ?? 'unknown',
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
