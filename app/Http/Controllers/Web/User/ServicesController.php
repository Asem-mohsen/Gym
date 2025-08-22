<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Services\{SiteSettingService , ServiceService , MembershipService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    public function __construct(protected ServiceService $serviceService, protected SiteSettingService $siteSettingService, protected MembershipService $membershipService)
    {
        $this->serviceService = $serviceService;
        $this->siteSettingService = $siteSettingService;
        $this->membershipService = $membershipService;
    }

    public function index(SiteSetting $siteSetting)
    {
        $services = $this->serviceService->getAvailableServices($siteSetting->id);
        $memberships = $this->membershipService->getMemberships($siteSetting->id);

        return view('user.services.index', compact('services', 'memberships', 'siteSetting'));
    }

    public function show(SiteSetting $siteSetting, Service $service)
    {
        // Load all necessary relationships
        $service->load([
            'branches',
            'galleries.media'
        ]);

        return view('user.services.show', compact('service', 'siteSetting'));
    }

    public function book(StoreBookingRequest $request, SiteSetting $siteSetting, Service $service)
    {
        $validated = $request->validated();

        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('user.login.index')->with('error', 'Please login to book this service.');
        }

        // Check if service is bookable
        if (!$service->isBookable()) {
            return redirect()->back()->with('error', 'This service is not available for booking.');
        }

        if ($service->requiresBookingPayment()) {
            $paymentMethod = $request->input('payment_method');
            
            if ($paymentMethod === 'card') {
                // Redirect to Paymob payment gateway
                return $this->redirectToPaymob($service, $request);
            } else {
                // Handle cash payment - create booking directly
                return $this->createCashBooking($service, $request, $user);
            }
        } else {
            // Free booking - create booking directly
            return $this->createFreeBooking($service, $request, $user);
        }
    }

    private function redirectToPaymob(Service $service, Request $request)
    {
        $booking = $this->createBookingRecord($service, $request, Auth::user(), 'pending');
        
        return redirect()->route('user.payment.create', ['siteSetting' => $request->route('siteSetting')->slug,'bookingId' => $booking->id])->with('info', 'Redirecting to payment gateway...');
    }

    private function createCashBooking(Service $service, Request $request, $user)
    {
        $this->createBookingRecord($service, $request, $user, 'cash_pending');
        
        return redirect()->back()->with('success', 'Booking created successfully! Please pay in cash at the branch.');
    }

    private function createFreeBooking(Service $service, Request $request, $user)
    {
        $this->createBookingRecord($service, $request, $user, 'completed');
        
        return redirect()->back()->with('success', 'Booking confirmed successfully!');
    }

    private function createBookingRecord(Service $service, Request $request, $user, $status)
    {
        return Booking::create([
                'user_id' => $user->id,
                'bookable_type' => Service::class,
                'bookable_id' => $service->id,
                'branch_id' => $request->input('branch_id'),
                'booking_date' => $request->input('booking_date') ?? now(),
                'status' => $status,
                'payment_method' => $request->input('payment_method', 'free'),
                'amount' => $service->price,
            ]);
    }
}
