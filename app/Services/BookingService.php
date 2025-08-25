<?php 
namespace App\Services;

use App\Repositories\BookingRepository;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

class BookingService
{
    protected $bookingRepository;
    protected $emailService;

    public function __construct(
        BookingRepository $bookingRepository,
        EmailService $emailService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->emailService = $emailService;
    }

    public function getBookings()
    {
        return $this->bookingRepository->getAllBookings();
    }

    public function createBooking(array $data)
    {
        $booking = $this->bookingRepository->createBooking($data);
        
        $this->sendBookingConfirmationEmail($booking);
        
        return $booking;
    }

    /**
     * Send booking confirmation email
     */
    private function sendBookingConfirmationEmail($booking): void
    {
        try {
            $this->emailService->sendBookingConfirmationEmail($booking);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function showBooking($booking)
    {
        return $this->bookingRepository->findById($booking->id);
    }

    public function deleteBooking($booking)
    {
        return $this->bookingRepository->deleteBooking($booking);
    }
}