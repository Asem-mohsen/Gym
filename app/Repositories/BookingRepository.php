<?php 
namespace App\Repositories;

use App\Models\Booking;

class BookingRepository
{
    public function createBooking(array $data, int $userId,string $bookableType, float $amount)
    {
        return Booking::create([
            'user_id'       => $userId,
            'bookable_type' => $bookableType,
            'bookable_id'   => $data['bookable_id'],
            'pricing_id'    => $data['pricing_id'] ?? null,
            'schedule_id'   => $data['schedule_id'] ?? null,
            'branch_id'     => $data['branch_id'] ?? null,
            'booking_date'  => $data['booking_date'] ?? now(),
            'status'        => 'pending',
            'amount'        => $amount,
        ]);
    }
}