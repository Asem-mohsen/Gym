<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    )
    {}

    public function index()
    {
        $payments = $this->paymentService->getPayments();
        
        /**
         * @var User $user
         */
        $user = Auth::user();
        $currentSite = $user->getCurrentSite();
        $currentGateway = $currentSite ? $currentSite->payment_gateway : 'paymob';

        return view('admin.payments.index' , get_defined_vars());
    }

    public function saveGateway(Request $request)
    {
        try {
            $request->validate([
                'gateway' => 'required|in:paymob,stripe,fawry'
            ]);

            /**
             * @var User $user
            */
            $user = Auth::user();
            $currentSite = $user->getCurrentSite();
            
            $currentSite->update([
                'payment_gateway' => $request->gateway
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment gateway preference saved successfully',
                'gateway' => $request->gateway
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save payment gateway preference: ' . $e->getMessage()
            ], 500);
        }
    }
}
