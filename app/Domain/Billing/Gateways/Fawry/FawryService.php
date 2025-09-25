<?php

namespace App\Domain\Billing\Gateways\Fawry;

use App\Domain\Billing\DTOs\PaymentRequest;
use Illuminate\Support\Facades\Log;

final class FawryService
{
    public function __construct(private FawryClient $client) {}

    /**
     * Get payment status from Fawry
     */
    public function getPaymentStatus(string $merchantRefNum, string $referenceNumber): array
    {
        try {
            return $this->client->getPaymentStatus($merchantRefNum, $referenceNumber);
        } catch (\Exception $e) {
            Log::error('Fawry payment status check failed', [
                'error' => $e->getMessage(),
                'merchant_ref_num' => $merchantRefNum,
                'reference_number' => $referenceNumber
            ]);

            throw new \RuntimeException('Failed to get Fawry payment status: ' . $e->getMessage());
        }
    }

    /**
     * Validate payment request before processing
     */
    public function validatePaymentRequest(PaymentRequest $req): bool
    {
        // Check required billing data
        if (empty($req->billingData['phone_number']) && empty($req->billingData['email'])) {
            return false;
        }

        // Check amount is positive
        if ($req->item->getAmount() <= 0) {
            return false;
        }

        // Check currency is supported
        $supportedCurrencies = ['EGP', 'USD'];
        if (!in_array($req->item->getCurrency(), $supportedCurrencies)) {
            return false;
        }

        return true;
    }

    /**
     * Get payment URL for redirect
     */
    public function getPaymentUrl(string $referenceNumber): string
    {
        return $this->client->getPaymentUrl($referenceNumber);
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(array $payload, string $providedSignature): bool
    {
        return $this->client->verifyWebhookSignature($payload, $providedSignature);
    }
}
