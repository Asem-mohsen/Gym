<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Models\User;
use App\Models\Membership;
use App\Models\Booking;


class BookingController extends Controller
{
    use ApiResponse ;

    // works
    // it return each booking with the bookable which is the something booked
    public function index()
    {
        $bookings = Booking::with('bookable')->get();

        return $this->data(compact('bookings') , 'data retrieved successfully');
    }

    // works
    // retrive the booked item for admin
    public function show(Booking $booking)
    {
        $booking->load('bookable');

        return $this->data(compact('booking') , 'booking data retrieved successfully');
    }

    //
    public function store(AddRequest $request)
    {
        $request->except('_method');
        $booking->load('bookable');

        return $this->data(compact('booking') , 'booking data retrieved successfully');
    }
}
