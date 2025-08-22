<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\{CreatePaymentIntentRequest, CreatePaymentWithBranchIntentRequest};
use App\Models\{Branch, Membership, Offer, Payment, SiteSetting, Subscription, ClassModel, Service, Booking};
use App\Services\Payments\PaymobService;
use App\Services\{PaymentService, SubscriptionService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log};

class PaymobPaymentController extends Controller
{
    public function __construct(
        private PaymobService $paymobService,
        private PaymentService $paymentService,
        private SubscriptionService $subscriptionService
    ) {}

    /**
     * Initialize payment process
     * Step 1: Check if gym has branches and handle branch selection
     */
    public function initializePayment(CreatePaymentIntentRequest $request)
    {
        try {
            $validated = $request->validated();

            $membership = Membership::findOrFail($validated['membership_id']);
            $user = Auth::user();
            $siteSetting = SiteSetting::findOrFail($validated['site_setting_id']);

            $branches = $siteSetting->branches;
            
            if ($branches->count() > 0) {

                return response()->json([
                    'success' => true,
                    'has_branches' => true,
                    'branches' => $branches->map(function($branch) {
                        return [
                            'id' => $branch->id,
                            'name' => $branch->name,
                            'location' => $branch->location
                        ];
                    }),
                    'message' => 'Please select a branch to continue'
                ]);
            }

            // No branches, proceed directly with payment
            return $this->processPayment($membership, $user, $validated['offer_id'] ?? null, $siteSetting, null);

        } catch (\Exception $e) {
            Log::error('Paymob payment initialization failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process payment with selected branch
     */
    public function processPaymentWithBranch(CreatePaymentWithBranchIntentRequest $request)
    {
        try {
            $validated = $request->validated();

            $membership = Membership::findOrFail($validated['membership_id']);
            $user = Auth::user();
            $siteSetting = SiteSetting::findOrFail($validated['site_setting_id']);
            $branch = Branch::findOrFail($request->branch_id);

            if ($branch->site_setting_id !== $siteSetting->id) {
                throw new \Exception('Invalid branch selected');
            }

            return $this->processPayment($membership, $user, $validated['offer_id'] ?? null, $siteSetting, $branch);

        } catch (\Exception $e) {
            Log::error('Paymob payment with branch failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['success' => false,'message' => 'Failed to process payment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Process payment following Paymob documentation steps
     */
    private function processPayment($membership, $user, $offerId, $siteSetting, $branch)
    {
        DB::beginTransaction();

        try {
            $offer = $offerId ? Offer::find($offerId) : null;

            // Step 1: Get authentication token
            $authToken = $this->paymobService->getAuthToken();
            if (!$authToken) {
                throw new \Exception('Failed to authenticate with Paymob');
            }

            // Step 2: Create order
            $orderData = $this->paymobService->createOrder($authToken, $membership, $user, $offer);
            if (!$orderData) {
                throw new \Exception('Failed to create order on Paymob');
            }

            // Step 3: Create payment key
            $paymentKeyData = $this->paymobService->createPaymentKey($authToken, $orderData, $membership, $user, $offer);
            if (!$paymentKeyData) {
                throw new \Exception('Failed to create payment key');
            }

            $payment = $this->paymentService->createPayment($membership, [
                'user_id' => $user->id,
                'site_setting_id' => $siteSetting->id,
                'amount' => $this->paymobService->calculateFinalPrice($membership, $offer),
                'offer_id' => $offer?->id,
                'branch_id' => $branch?->id,
                'status' => 'pending',
                'paymob_order_id' => $orderData['id'],
                'paymob_payment_key' => $paymentKeyData['token'],
                'currency' => 'EGP',
            ]);

            DB::commit();

            // Step 4: Return payment URL for redirection
            $paymentUrl = $this->paymobService->getIframeUrl($paymentKeyData['token']);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'payment_url' => $paymentUrl,
                    'payment_id' => $payment->id
                ],
                'message' => 'Payment initialized successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Handle Paymob payment callback
     * Step 5: Verify payment and process based on payment type
     */
    public function handleCallback(Request $request)
    {
        try {
            $callbackData = $request->all();
            $orderId = $callbackData['order'] ?? null;
            $transactionId = $callbackData['id'] ?? null;
            $success = $callbackData['success'] ?? false;
            $pending = $callbackData['pending'] ?? false;

            $isVerified = $this->paymobService->verifyPayment($callbackData);
            
            if (!$isVerified) {
                return $this->redirectToFailure(null, 'Payment verification failed');
            }

            $payment = Payment::where('paymob_order_id', $orderId)
                ->with(['siteSetting', 'branch.siteSetting', 'paymentable'])
                ->first();

            if (!$payment) {
                return $this->redirectToFailure(null, 'Payment not found');
            }

            DB::beginTransaction();

            if ($success) {

                if ($payment->status !== 'completed') {

                    $this->paymentService->updatePayment($payment, $payment->paymentable, [
                        'status' => 'completed',
                        'paymob_transaction_id' => $transactionId,
                        'completed_at' => now(),
                    ]);

                    // Process based on payment type
                    $this->processSuccessfulPayment($payment);
                }

                DB::commit();

                return $this->redirectToSuccess($payment);

            } else {
                
                $this->paymentService->updatePayment($payment, $payment->paymentable, [
                    'status' => 'failed',
                    'paymob_transaction_id' => $transactionId,
                    'failed_at' => now(),
                ]);

                DB::commit();
                
                return $this->redirectToFailure($payment, 'Payment failed. Please try again.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Paymob callback processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'callback_data' => $request->all()
            ]);
            return $this->redirectToFailure(null, 'An error occurred while processing your payment.');
        }
    }

    /**
     * Process successful payment based on payment type
     */
    private function processSuccessfulPayment(Payment $payment): void
    {
        $paymentable = $payment->paymentable;
        
        if (!$paymentable) {
            Log::warning('Payment has no paymentable model', ['payment_id' => $payment->id]);
            return;
        }

        switch ($payment->paymentable_type) {
            case Membership::class:
                $this->processMembershipPayment($payment, $paymentable);
                break;
                
            case ClassModel::class:
                $this->processClassPayment($payment, $paymentable);
                break;
                
            case Service::class:
                $this->processServicePayment($payment, $paymentable);
                break;
                
            default:
                Log::warning('Unknown payment type', [
                    'payment_id' => $payment->id,
                    'paymentable_type' => $payment->paymentable_type
                ]);
        }
    }

    /**
     * Process membership payment - create subscription
     */
    private function processMembershipPayment(Payment $payment, Membership $membership): void
    {
        $existingSubscription = Subscription::where([
            'user_id' => $payment->user_id,
            'membership_id' => $payment->paymentable_id,
            'branch_id' => $payment->branch_id,
            'status' => 'active'
        ])->first();

        if (!$existingSubscription) {
            $this->subscriptionService->createSubscription([
                'user_id' => $payment->user_id,
                'membership_id' => $payment->paymentable_id,
                'branch_id' => $payment->branch_id,
                'start_date' => now(),
                'end_date' => $membership->calculateEndDate(),
                'status' => 'active',
            ]);
        }
    }

    /**
     * Process class payment - update booking status
     */
    private function processClassPayment(Payment $payment, ClassModel $class): void
    {
        $booking = Booking::where([
            'user_id' => $payment->user_id,
            'bookable_type' => ClassModel::class,
            'bookable_id' => $class->id,
            'status' => 'pending'
        ])->first();

        if ($booking) {
            $booking->update(['status' => 'completed']);
        }
    }

    /**
     * Process service payment - update booking status
     */
    private function processServicePayment(Payment $payment, Service $service): void
    {
        $booking = Booking::where([
            'user_id' => $payment->user_id,
            'bookable_type' => Service::class,
            'bookable_id' => $service->id,
            'status' => 'pending'
        ])->first();

        if ($booking) {
            $booking->update(['status' => 'completed']);
        }
    }

    /**
     * Redirect to success page based on payment type
     */
    private function redirectToSuccess(Payment $payment)
    {
        $siteSettingSlug = $this->getPaymentSiteSettingSlug($payment);
        
        if (!$siteSettingSlug) {
            return $this->redirectToFailure(null, 'Payment completed but gym configuration not found');
        }

        switch ($payment->paymentable_type) {
            case Membership::class:
                return redirect()->route('user.memberships.index', ['siteSetting' => $siteSettingSlug])
                    ->with('success', 'Membership payment completed successfully!');
                    
            case ClassModel::class:
                return redirect()->route('user.classes.index', ['siteSetting' => $siteSettingSlug])
                    ->with('success', 'Class booking payment completed successfully!');
                    
            case Service::class:
                return redirect()->route('user.services', ['siteSetting' => $siteSettingSlug])
                    ->with('success', 'Service booking payment completed successfully!');
                    
            default:
                return redirect()->route('user.dashboard', ['siteSetting' => $siteSettingSlug])
                    ->with('success', 'Payment completed successfully!');
        }
    }

    /**
     * Get the correct siteSetting slug for a payment
     * Priority: 1. Direct siteSetting, 2. Branch's siteSetting
     */
    private function getPaymentSiteSettingSlug(Payment $payment): ?string
    {
        // First try direct siteSetting
        if ($payment->siteSetting) {
            return $payment->siteSetting->slug;
        }
        
        // Then try branch's siteSetting
        if ($payment->branch && $payment->branch->siteSetting) {
            return $payment->branch->siteSetting->slug;
        }
        
        return null;
    }

    /**
     * Redirect to failure page
     */
    private function redirectToFailure(?Payment $payment = null, string $message = 'Payment failed')
    {
        if (request()->isMethod('get')) {
            if ($payment) {
                $siteSettingSlug = $this->getPaymentSiteSettingSlug($payment);
                if ($siteSettingSlug) {
                    switch ($payment->paymentable_type) {
                        case Membership::class:
                            return redirect()->route('user.memberships.show', [
                                'siteSetting' => $siteSettingSlug,
                                'membership' => $payment->paymentable_id
                            ])->with('warning', $message);
                        case ClassModel::class:
                            return redirect()->route('user.classes.index', ['siteSetting' => $siteSettingSlug])
                                ->with('warning', $message);
                        case Service::class:
                            return redirect()->route('user.services', ['siteSetting' => $siteSettingSlug])
                                ->with('warning', $message);
                    }
                }
            }
            return redirect()->route('payments.paymob.failure')->with('error', $message);
        }
        
        return response()->json(['success' => false, 'message' => $message], 400);
    }
}
