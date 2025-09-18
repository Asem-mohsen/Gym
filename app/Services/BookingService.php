<?php 
namespace App\Services;

use Exception;
use App\Repositories\BookingRepository;
use App\Services\{EmailService, PricingService};
use Illuminate\Support\Facades\Log;
use App\Models\{Booking, Service, ClassModel, Membership};

class BookingService
{
    protected $bookingRepository;
    protected $emailService;
    protected $pricingService;

    public function __construct(
        BookingRepository $bookingRepository,
        EmailService $emailService,
        PricingService $pricingService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->emailService = $emailService;
        $this->pricingService = $pricingService;
    }

    public function createBooking(array $data, int $userId): Booking
    {
        $typeMap = [
            'service'    => Service::class,
            'class'      => ClassModel::class,
            'membership' => Membership::class,
        ];

        $amount = $this->pricingService->calculateAmount($data['bookable_type'], $data['bookable_id'], $data['pricing_id'] ?? null);

        $booking = $this->bookingRepository->createBooking($data, $userId, $typeMap[$data['bookable_type']], $amount);

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
        } catch (Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}