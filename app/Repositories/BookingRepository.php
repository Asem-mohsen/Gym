<?php 
namespace App\Repositories;

use App\Models\Booking;

class BookingRepository
{
    public function getAllBookings()
    {
        return Booking::with('bookable')->get();
    }

    public function createBooking(array $data)
    {
        return Booking::create($data);
    }

    public function deleteBooking(Booking $booking)
    {
        $booking->delete();
    }

    public function findById(int $id): ?Booking
    {
        return Booking::find($id);
    }
}