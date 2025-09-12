<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\CreatePaymentIntentRequest;
use App\Models\Membership;
use App\Models\Offer;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Create a Stripe payment intent for membership enrollment
     */
    public function createPaymentIntent(CreatePaymentIntentRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Get the membership
            $membership = Membership::findOrFail($request->membership_id);
            
            // Calculate final price (considering offers)
            $finalPrice = $membership->price;
            $offerId = null;
            
            if ($request->offer_id) {
                $offer = Offer::find($request->offer_id);
                if ($offer && $offer->isActive()) {
                    $finalPrice = $this->calculateDiscountedPrice($membership->price, $offer);
                    $offerId = $offer->id;
                }
            }

            // Set Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($finalPrice * 100), // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'membership_id' => $membership->id,
                    'offer_id' => $offerId,
                    'site_setting_id' => $request->site_setting_id,
                    'user_id' => Auth::id(),
                    'membership_name' => $membership->name,
                    'original_price' => $membership->price,
                    'final_price' => $finalPrice,
                ],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                    'amount' => $finalPrice,
                    'currency' => 'usd',
                ],
                'message' => 'Payment intent created successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm payment and save to database
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $paymentIntentId = $request->payment_intent_id;
            
            // Verify payment with Stripe
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($paymentIntent->status !== 'succeeded') {
                throw new Exception('Payment not completed');
            }

            // Extract metadata
            $metadata = $paymentIntent->metadata;
            
            // Create payment record
            $paymentData = [
                'user_id' => $metadata->user_id,
                'amount' => $paymentIntent->amount / 100, // Convert from cents
                'offer_id' => $metadata->offer_id ?: null,
                'site_setting_id' => $metadata->site_setting_id,
                'paymentable_type' => Membership::class,
                'paymentable_id' => $metadata->membership_id,
                'status' => 'completed',
                'stripe_payment_intent_id' => $paymentIntentId,
            ];

            $payment = $this->paymentService->createPayment(
                new Membership(['id' => $metadata->membership_id]),
                $paymentData
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $payment,
                'message' => 'Payment confirmed successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate discounted price based on offer
     */
    private function calculateDiscountedPrice(float $originalPrice, Offer $offer): float
    {
        if ($offer->discount_type === 'percentage') {
            return $originalPrice * (1 - ($offer->discount_value / 100));
        } elseif ($offer->discount_type === 'fixed') {
            return max(0, $originalPrice - $offer->discount_value);
        }
        
        return $originalPrice;
    }

    /**
     * Mobile API: Enroll in membership with payment
     */
    public function mobileEnrollMembership(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'membership_id' => 'required|exists:memberships,id',
                'offer_id' => 'nullable|exists:offers,id',
                'site_setting_id' => 'required|exists:site_settings,id',
                'payment_method_id' => 'required|string',
            ]);

            DB::beginTransaction();

            // Get the membership
            $membership = Membership::findOrFail($request->membership_id);
            
            // Calculate final price (considering offers)
            $finalPrice = $membership->price;
            $offerId = null;
            
            if ($request->offer_id) {
                $offer = Offer::find($request->offer_id);
                if ($offer && $offer->isActive()) {
                    $finalPrice = $this->calculateDiscountedPrice($membership->price, $offer);
                    $offerId = $offer->id;
                }
            }

            // Set Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($finalPrice * 100), // Convert to cents
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirm' => true,
                'return_url' => config('app.url') . '/mobile/payment-success',
                'metadata' => [
                    'membership_id' => $membership->id,
                    'offer_id' => $offerId,
                    'site_setting_id' => $request->site_setting_id,
                    'user_id' => Auth::id(),
                    'membership_name' => $membership->name,
                    'original_price' => $membership->price,
                    'final_price' => $finalPrice,
                ],
            ]);

            if ($paymentIntent->status === 'succeeded') {
                // Create payment record
                $paymentData = [
                    'user_id' => Auth::id(),
                    'amount' => $finalPrice,
                    'offer_id' => $offerId,
                    'site_setting_id' => $request->site_setting_id,
                    'paymentable_type' => Membership::class,
                    'paymentable_id' => $membership->id,
                    'status' => 'completed',
                    'stripe_payment_intent_id' => $paymentIntent->id,
                ];

                $payment = $this->paymentService->createPayment(
                    $membership,
                    $paymentData
                );

                DB::commit();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'payment' => $payment,
                        'membership' => $membership,
                        'payment_intent_id' => $paymentIntent->id,
                        'amount_paid' => $finalPrice,
                    ],
                    'message' => 'Membership enrollment successful'
                ]);
            } else {
                throw new Exception('Payment failed: ' . $paymentIntent->status);
            }

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Enrollment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mobile API: Get payment status
     */
    public function getPaymentStatus(string $paymentIntentId): JsonResponse
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            $payment = Payment::where('stripe_payment_intent_id', $paymentIntentId)->first();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'payment_intent_status' => $paymentIntent->status,
                    'payment_record' => $payment,
                    'amount' => $paymentIntent->amount / 100,
                    'currency' => $paymentIntent->currency,
                ],
                'message' => 'Payment status retrieved successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment status: ' . $e->getMessage()
            ], 500);
        }
    }
}
