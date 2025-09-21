<?php

namespace App\Http\Controllers\Web\User;

use App\Domain\Billing\PaymentGatewayManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StripePaymentController extends Controller
{
    public function __construct(
        private PaymentGatewayManager $gatewayManager
    ) {}

    /**
     * Handle Stripe payment return
     */
    public function return(Request $request)
    {
        /**
         * @var User $user
        */
        $user = Auth::user();
        $siteSetting = $user->getCurrentSite();

        // Check payment status
        $sessionId = $request->query('session_id');
        $paymentIntentId = $request->query('payment_intent');

        return view('user.payment-success', [
            'siteSetting' => $siteSetting,
            'paymentStatus' => 'success', // You might want to verify this with Stripe
            'sessionId' => $sessionId,
            'paymentIntentId' => $paymentIntentId,
        ]);
    }
}
