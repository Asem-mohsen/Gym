<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Services\{BookingService, PaymentService};
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private PaymentService $paymentService
    ) {}

    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::guard('sanctum')->user();
        $method = $validated['method'] ?? 'cash';

        $booking = $this->bookingService->createBooking($validated, $user->id);

        if ($validated['bookable_type'] === 'membership' && $method === 'cash') {
            return failureResponse('Membership cannot be paid with cash.', 422);
        }

        if ($method === 'cash' && in_array($validated['bookable_type'], ['service', 'class'])) {
            $payment = $this->paymentService->createCashPayment($booking, $user, $method);

            return successResponse([
                'booking_id' => $booking->id,
                'payment_id' => $payment->id,
                'status'     => 'cash_pending',
                'amount'     => $booking->amount,
                'currency'   => 'EGP',
            ]);
        }

        $intent = $this->paymentService->createGatewayPayment($booking, $method, $user);

        return successResponse([
            'booking_id'          => $booking->id,
            'gateway'             => $intent->gateway,
            'gateway_order_id'    => $intent->gatewayOrderId,
            'payment_key'         => $intent->paymentKey,
            'iframe_url'          => $intent->iframeUrl,
            'wallet_redirect_url' => $intent->walletRedirectUrl,
        ]);
    }
}