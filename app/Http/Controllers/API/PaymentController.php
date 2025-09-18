<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Str;
use App\Domain\Billing\DTOs\PaymentRequest;
use App\Domain\Billing\Gateways\Paymob\PaymobGateway;
use App\Enums\PaymentMethod;
use App\Http\Requests\Payment\CreatePaymentIntent;

class PaymentController extends Controller
{
    public function __construct(
        private PaymobGateway $gateway,
    ) {}

    public function intent(CreatePaymentIntent $request)
    {
        $validated = $request->validated();

        // 1. Load the Booking (this implements Purchasable)
        $booking = Booking::findOrFail($validated['booking_id']);

        // 2. Create the PaymentRequest DTO
        $paymentRequest = new PaymentRequest(
            item: $booking,
            method: PaymentMethod::from($validated['method']),
            merchantOrderId: Str::ulid(), // your unique reference
            returnUrl: route('paymob.return'), // optional for web
            billingData: [
                'email' => $booking->getCustomerEmail(),
                'phone_number' => $booking->getCustomerPhone(),
                'first_name' => $booking->user->first_name ?? 'Guest',
                'last_name'  => $booking->user->last_name ?? 'User',
                'street' => $booking->user->address ?? 'NA',
                'city' => $booking->user->city,
                'country' => $booking->user->country,
                'site_setting_id' => $booking->user->getCurrentSite()->id,
            ],
            user: $booking->user
        );

        // 3. Hand off to the gateway
        $intent = $this->gateway->createIntent($paymentRequest);

        // 4. Return the intent JSON to client
        return successResponse([
            'gateway' => $intent->gateway,
            'gateway_order_id' => $intent->gatewayOrderId,
            'payment_key' => $intent->paymentKey,
            'iframe_url' => $intent->iframeUrl,
            'wallet_redirect_url' => $intent->walletRedirectUrl,
        ]);
        
    }
}
