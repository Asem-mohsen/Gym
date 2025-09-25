<?php

namespace App\Http\Controllers\Web\User;

use App\Domain\Billing\DTOs\PaymentRequest;
use App\Domain\Billing\PaymentGatewayManager;
use App\Domain\Billing\Gateways\Fawry\FawryService;
use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Models\Payment;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class FawryPaymentController extends Controller
{
    public function __construct(
        private PaymentGatewayManager $gatewayManager,
        private FawryService $fawryService
    ) {}

    /**
     * Process Fawry payment request
     */
    public function processPayment(StoreBookingRequest $request)
    {
        try {
            $validated = $request->validated();
            
            /** @var User $user */
            $user = Auth::user();
            $siteSetting = $user->getCurrentSite();

            // Create booking first
            $booking = app(\App\Services\BookingService::class)->createBooking($validated, $user->id);

            if (isset($validated['is_free']) && $validated['is_free']) {
                return redirect()->back()->with('success', 'Booking created successfully');
            }

            // Get Fawry gateway
            $gateway = $this->gatewayManager->getGatewayByName('fawry');

            // Create payment request
            $paymentRequest = new PaymentRequest(
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
                    'customer_id' => $user->id,
                ],
                user: $user
            );

            // Validate payment request
            if (!$this->fawryService->validatePaymentRequest($paymentRequest)) {
                return redirect()->back()->withErrors(['payment' => 'Invalid payment request data']);
            }

            // Create payment intent
            $intent = $gateway->createIntent($paymentRequest);

            // Redirect to Fawry payment page
            return redirect()->away($intent->getPaymentUrl());

        } catch (\Exception $e) {
            Log::error('Fawry payment processing failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $validated ?? []
            ]);

            return redirect()->back()->withErrors(['payment' => 'Payment processing failed. Please try again.']);
        }
    }

    /**
     * Handle Fawry payment return/callback
     */
    public function handleReturn(Request $request)
    {
        try {
            $merchantRefNum = $request->input('merchantRefNum');
            $referenceNumber = $request->input('referenceNumber');
            $statusCode = $request->input('statusCode');

            if (!$merchantRefNum) {
                return redirect()->route('user.payment.failed')->withErrors(['payment' => 'Invalid payment reference']);
            }

            // Find payment record
            $payment = Payment::where('merchant_order_id', $merchantRefNum)->first();

            if (!$payment) {
                return redirect()->route('user.payment.failed')->withErrors(['payment' => 'Payment not found']);
            }

            /** @var User $user */
            $user = Auth::user();
            $siteSetting = $user->getCurrentSite();

            // Check payment status
            if ($this->isPaymentSuccessful($statusCode)) {
                return view('user.payment-success', compact('siteSetting', 'payment'));
            } else {
                return redirect()->route('user.payment.failed')->withErrors(['payment' => 'Payment was not successful']);
            }

        } catch (\Exception $e) {
            Log::error('Fawry payment return handling failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->route('user.payment.failed')->withErrors(['payment' => 'Payment verification failed']);
        }
    }

    /**
     * Get payment status from Fawry
     */
    public function checkStatus(Request $request)
    {
        try {
            $merchantRefNum = $request->input('merchant_ref_num');
            $referenceNumber = $request->input('reference_number');

            if (!$merchantRefNum || !$referenceNumber) {
                return response()->json(['error' => 'Missing required parameters'], 400);
            }

            $status = $this->fawryService->getPaymentStatus($merchantRefNum, $referenceNumber);

            return response()->json([
                'success' => true,
                'status' => $status
            ]);

        } catch (\Exception $e) {
            Log::error('Fawry status check failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json(['error' => 'Status check failed'], 500);
        }
    }

    /**
     * Get the appropriate return URL
     */
    private function getReturnUrl(SiteSetting $siteSetting): string
    {
        return route('user.payment.fawry.return', ['siteSetting' => $siteSetting->slug]);
    }

    /**
     * Check if payment status indicates success
     */
    private function isPaymentSuccessful(?int $statusCode): bool
    {
        $successCodes = [200, 201, 202];
        return in_array($statusCode, $successCodes);
    }
}
