<?php

namespace App\Http\Controllers\Web\User;

use App\Domain\Billing\DTOs\PaymentRequest;
use App\Domain\Billing\PaymentGatewayManager;
use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\BookingService;

class CheckoutController extends Controller
{
    public function __construct(
        private PaymentGatewayManager $gatewayManager,
        private BookingService $bookingService,
    ) {}

    public function create(StoreBookingRequest $request)
    {
        $validated = $request->validated();

        /**
         * @var User $user
         */
        $user = Auth::user();
        $siteSetting = $user->getCurrentSite();

        $booking = $this->bookingService->createBooking($validated, $user->id);

        if (isset($validated['is_free']) && $validated['is_free']) {
            return redirect()->back()->with('success', 'Booking created successfully');
        }

        $gateway = $this->gatewayManager->getGateway($siteSetting);

        $req = new PaymentRequest(
            item: $booking,
            method: PaymentMethod::from($request->input('method', 'card')),
            merchantOrderId: Str::ulid(),
            returnUrl: $this->getReturnUrl($siteSetting),
            billingData: [
                'email' => $booking->getCustomerEmail(),
                'phone_number' => $booking->getCustomerPhone(),
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'country' => $user->country ?? 'Egypt',
                'street' => $user->address ?? 'NA',
                'city' => $user->city ?? 'Cairo',
                'site_setting_id' => $siteSetting->id,
            ],
            user: $user
        );

        $intent = $gateway->createIntent($req);

        if ($intent->isHostedPayment()) {
            return redirect()->away($intent->getPaymentUrl());
        }

        // For non-hosted payments (like Stripe Elements), return JSON
        return response()->json([
            'success' => true,
            'data' => $intent->getFrontendConfig()
        ]);
    }

    public function return(Request $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        
        $siteSetting = $user->getCurrentSite();

        return view('user.payment-success' , compact('siteSetting')); 
    }

    /**
     * Get the appropriate return URL based on the gateway
     */
    private function getReturnUrl($siteSetting): string
    {
        $gateway = $siteSetting?->payment_gateway ?? config('services.default_payment_gateway', 'paymob');
        
        return match ($gateway) {
            'stripe' => route('paymob.return'),
            'paymob' => route('paymob.return'),
            'fawry' => route('user.payment.fawry.return', ['siteSetting' => $siteSetting->slug]),
            default => route('paymob.return')
        };
    }
}