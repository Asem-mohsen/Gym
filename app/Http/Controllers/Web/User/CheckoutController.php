<?php

namespace App\Http\Controllers\Web\User;

use App\Domain\Billing\DTOs\PaymentRequest;
use App\Domain\Billing\Gateways\Paymob\PaymobGateway;
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
    public function __construct(private PaymobGateway $gateway, private BookingService $bookingService,) {}

    public function create(StoreBookingRequest $request)
    {
        $validated = $request->validated();

        /**
         * @var User $user
         */
        $user = Auth::user();

        $booking = $this->bookingService->createBooking($validated, $user->id);

        $req = new PaymentRequest(
            item: $booking,
            method: PaymentMethod::from($request->input('method', 'card')),
            merchantOrderId: Str::ulid(),
            returnUrl: route('paymob.return'),
            billingData: [
                'email' => $booking->getCustomerEmail(),
                'phone_number' => $booking->getCustomerPhone(),
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'country' => $user->country ?? 'Egypt',
                'street' => $user->address ?? 'NA',
                'city' => $user->city ?? 'Cairo',
                'site_setting_id' => $user->getCurrentSite()->id,
            ],
            user: $user
        );
        $intent = $this->gateway->createIntent($req);

        dd($intent->iframeUrl);
        return $intent->iframeUrl
            ? redirect()->away($intent->iframeUrl)
            : response()->json($intent);
    }

    public function return(Request $request)
    {
        return view('user.payment-success'); 
    }

}