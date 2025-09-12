<?php

namespace App\Http\Controllers\API;

use App\Models\Membership;
use App\Models\User;
use App\Models\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddBookingRequest;
use App\Models\Booking;
use App\Models\SiteSetting;
use App\Services\BookingService;
use Exception;
use Illuminate\Http\Request;

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

    public function bookMembership(Request $request, SiteSetting $gym)
    {
        try {
            $validatedData = $request->validate([
                'membership_id' => 'required|exists:memberships,id',
                'user_id' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            // Validate that the membership belongs to the gym
            $membership = Membership::where('id', $validatedData['membership_id'])
                ->where('site_setting_id', $gym->id)
                ->firstOrFail();

            $booking = $this->bookingService->createBooking([
                'user_id' => $validatedData['user_id'],
                'bookable_type' => Membership::class,
                'bookable_id' => $validatedData['membership_id'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
            ]);

            return successResponse(compact('booking'), 'Membership booking created successfully');
        } catch (Exception $e) {
            return failureResponse('Error creating membership booking, please try again.');
        }
    }

    public function bookCoach(Request $request, SiteSetting $gym)
    {
        try {
            $validatedData = $request->validate([
                'coach_id' => 'required|exists:users,id',
                'user_id' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'notes' => 'nullable|string|max:500',
            ]);

            // Validate that the coach belongs to the gym
            $coach = User::where('id', $validatedData['coach_id'])
                ->whereHas('gyms', function($query) use ($gym) {
                    $query->where('site_setting_id', $gym->id);
                })
                ->firstOrFail();

            $booking = $this->bookingService->createBooking([
                'user_id' => $validatedData['user_id'],
                'coach_id' => $validatedData['coach_id'],
                'bookable_type' => User::class,
                'bookable_id' => $validatedData['coach_id'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'notes' => $validatedData['notes'] ?? null,
            ]);

            return successResponse(compact('booking'), 'Coach booking created successfully');
        } catch (Exception $e) {
            return failureResponse('Error creating coach booking, please try again.');
        }
    }

    public function bookService(Request $request, SiteSetting $gym)
    {
        try {
            $validatedData = $request->validate([
                'service_id' => 'required|exists:services,id',
                'user_id' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'notes' => 'nullable|string|max:500',
            ]);

            // Validate that the service belongs to the gym
            $service = Service::where('id', $validatedData['service_id'])
                ->where('site_setting_id', $gym->id)
                ->firstOrFail();

            $booking = $this->bookingService->createBooking([
                'user_id' => $validatedData['user_id'],
                'bookable_type' => Service::class,
                'bookable_id' => $validatedData['service_id'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'notes' => $validatedData['notes'] ?? null,
            ]);

            return successResponse(compact('booking'), 'Service booking created successfully');
        } catch (Exception $e) {
            return failureResponse('Error creating service booking, please try again.');
        }
    }
}
