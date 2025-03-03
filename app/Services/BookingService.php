<?php 
namespace App\Services;

use App\Repositories\BookingRepository;

class BookingService
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function getBookings()
    {
        return $this->bookingRepository->getAllBookings();
    }

    public function createBooking(array $data)
    {
        return $this->bookingRepository->createBooking($data);
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