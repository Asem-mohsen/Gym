<?php 
namespace App\Services;

use Exception;
use App\Models\Booking;
use App\Repositories\PaymentRepository;
use App\Services\{SiteSettingService, EmailService};
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected int $siteSettingId;

    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected SiteSettingService $siteSettingService,
        protected EmailService $emailService
    )
    {
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    public function getPayments()
    {
        return $this->paymentRepository->getPayments($this->siteSettingId);
    }

    public function createPayment($paymentable, array $data)
    {
        $payment = $this->paymentRepository->createPayment($paymentable, $data);
        
        if ($payment->status === 'completed') {
            $this->sendConfirmationEmail($payment);
        }
        
        return $payment;
    }

    public function updatePayment($payment, $paymentable, array $data)
    {
        $updatedPayment = $this->paymentRepository->updatePayment($payment, $paymentable, $data);
        
        // Send confirmation email if payment status changed to completed
        if (isset($data['status']) && $data['status'] === 'completed' && $payment->status !== 'completed') {
            $this->sendConfirmationEmail($updatedPayment);
        }
        
        return $updatedPayment;
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
