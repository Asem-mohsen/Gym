<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Offer;
use App\Models\Payment;
use App\Services\PaymentService;
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
    public function createPaymentIntent(Request $request)
    {
        try {
            $request->validate([
                'membership_id' => 'required|exists:memberships,id',
                'offer_id' => 'nullable|exists:offers,id',
                'site_setting_id' => 'required|exists:site_settings,id',
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

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent: ' . $e->getMessage()
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
}
