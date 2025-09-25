<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public ?Payment $payment;
    public SiteSetting $gym;

    public function __construct(Booking $booking, ?Payment $payment, SiteSetting $gym)
    {
        $this->booking = $booking;
        $this->payment = $payment;
        $this->gym = $gym;
    }

    public function envelope(): Envelope
    {
        $bookableType = $this->getBookableType();
        return new Envelope(
            subject: 'Booking Confirmation - ' . $bookableType . ' at ' . $this->gym->gym_name,
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
            view: 'emails.booking-confirmation',
            with: [
                'userName' => $this->booking->user->name,
                'gymName' => $this->gym->gym_name,
                'gymLogo' => $this->gym->getFirstMediaUrl('email_logo') ?: $this->gym->getFirstMediaUrl('gym_logo'),
                'contactEmail' => $this->gym->contact_email,
                'booking' => $this->booking,
                'payment' => $this->payment,
                'bookableType' => $this->getBookableType(),
                'bookableName' => $this->getBookableName(),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function getBookableType(): string
    {
        return match ($this->booking->bookable_type) {
            'App\Models\Membership' => 'Membership',
            'App\Models\Service' => 'Service',
            'App\Models\ClassModel' => 'Class',
            default => 'Booking'
        };
    }

    private function getBookableName(): string
    {
        $bookable = $this->booking->bookable;
        
        if (!$bookable) {
            return 'Unknown';
        }

        return match ($this->booking->bookable_type) {
            'App\Models\Membership' => $bookable->name,
            'App\Models\Service' => $bookable->name,
            'App\Models\ClassModel' => $bookable->name,
            default => 'Unknown'
        };
    }
}
