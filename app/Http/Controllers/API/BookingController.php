<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Exception;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        try {
            $bookings = $this->bookingService->getBookings();
            return successResponse(compact('bookings'), 'Bookings retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving bookings, please try again.');
        }
    }

    public function show(Booking $booking)
    {
        try {
            $booking = $this->bookingService->showBooking($booking);
            $booking->load('bookable');
            return successResponse(compact('booking'), 'Booking retrieved successfully');
        } catch (Exception $e) {
            return failureResponse('Error retrieving bookings, please try again.');
        }
    }

    public function store(AddBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());
            $booking->load('bookable');
            return successResponse(compact('booking'), 'Booking created successfully');
        } catch (Exception $e) {
            return failureResponse('Error creating bookings, please try again.');
        }
    }
}
