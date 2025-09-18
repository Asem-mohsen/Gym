<?php 
namespace App\Services;

use App\Domain\Billing\DTOs\PaymentRequest;
use App\Domain\Billing\Gateways\Paymob\PaymobGateway;
use App\Enums\PaymentMethod;
use Exception;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\PaymentRepository;
use App\Services\{SiteSettingService, EmailService};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected SiteSettingService $siteSettingService,
        protected EmailService $emailService,
        private PaymobGateway $gateway
    )
    {}

    public function getPayments()
    {
        return $this->paymentRepository->getPayments($this->siteSettingService->getCurrentSiteSettingId());
    }

    public function createCashPayment(Booking $booking, $user, string $method): Payment
    {
        $payment = Payment::create([
            'paymentable_type' => $booking->bookable_type,
            'paymentable_id' => $booking->bookable_id,
            'site_setting_id' => $user->getCurrentSite()->id,
            'user_id'    => $user->id,
            'gateway'    => null,
            'payment_method' => $method,
            'status'     => 'cash_pending',
            'currency'   => 'EGP',
            'amount'     => $booking->amount,
            'meta'       => [
                'method' => $method,
            ],
        ]);

        $this->sendConfirmationEmail($payment);

        return $payment;
    }

    public function createGatewayPayment(Booking $booking, string $method, User $user)
    {
        $paymentRequest = new PaymentRequest(
            item: $booking,
            method: PaymentMethod::from($method),
            merchantOrderId: Str::ulid(),
            returnUrl: route('paymob.return'),
            billingData: [
                'email'       => $booking->getCustomerEmail(),
                'phone_number'=> $booking->getCustomerPhone(),
                'first_name'  => $booking->user->first_name ?? 'Guest',
                'last_name'   => $booking->user->last_name ?? 'User',
                'street'      => $booking->user->address ?? 'NA',
                'city'        => $booking->user->city,
                'country'     => $booking->user->country,
                'site_setting_id' => $user->getCurrentSite()->id
            ],
            user: $booking->user
        );

        return $this->gateway->createIntent($paymentRequest);
    }

    /**
     * Send confirmation email for completed payments
     */
    private function sendConfirmationEmail($payment): void
    {
        try {
            $booking = Booking::where('user_id', $payment->user_id)
                ->where('bookable_type', $payment->paymentable_type)
                ->where('bookable_id', $payment->paymentable_id)
                ->latest()
                ->first();

            if ($booking) {
                $this->emailService->sendBookingConfirmationEmail($booking, $payment);
            }
        } catch (Exception $e) {
            Log::error('Failed to send confirmation email', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
