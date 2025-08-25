<?php

namespace App\Services;

use App\Mail\{WelcomeEmail, BookingConfirmationEmail, AccountDeletionEmail};
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail(User $user, SiteSetting $gym): bool
    {
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user, $gym));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'gym_id' => $gym->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send booking confirmation email
     */
    public function sendBookingConfirmationEmail(Booking $booking, ?Payment $payment = null): bool
    {
        try {
            $user = $booking->user;
            $gym = $booking->bookable->siteSetting ?? $booking->bookable->gym;
            
            Mail::to($user->email)->send(new BookingConfirmationEmail($booking, $payment, $gym));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send account deletion farewell email
     */
    public function sendAccountDeletionEmail(User $user, SiteSetting $gym): bool
    {
        try {
            Mail::to($user->email)->send(new AccountDeletionEmail($user, $gym));
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send account deletion email', [
                'user_id' => $user->id,
                'gym_id' => $gym->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
