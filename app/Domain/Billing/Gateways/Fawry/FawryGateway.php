<?php

namespace App\Domain\Billing\Gateways\Fawry;

use App\Domain\Billing\PaymentRecorder;
use App\Domain\Billing\Contracts\PaymentGateway;
use App\Domain\Billing\DTOs\PaymentRequest;
use App\Domain\Billing\DTOs\PaymentIntent;
use App\Enums\PaymentMethod;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

final class FawryGateway implements PaymentGateway
{
    public function __construct(
        private FawryClient $client,
        private PaymentRecorder $recorder
    ) {}

    public function createIntent(PaymentRequest $req): PaymentIntent
    {
        // Validate client configuration
        if (!$this->client->validateConfiguration()) {
            throw new \RuntimeException('Fawry gateway is not properly configured');
        }

        // Prepare payment data
        $paymentData = [
            'merchant_ref_num' => $req->merchantOrderId,
            'customer_mobile' => $req->billingData['phone_number'] ?? '01000000000',
            'customer_email' => $req->billingData['email'] ?? 'customer@example.com',
            'customer_profile_id' => $req->billingData['customer_id'] ?? null,
            'amount' => $req->item->getAmount(),
            'currency_code' => $req->item->getCurrency(),
            'language' => 'en-gb',
            'charge_items' => [
                [
                    'itemId' => $req->item->getPurchasableId() ?? '1',
                    'description' => $req->item->getTitle(),
                    'price' => $req->item->getAmount(),
                    'quantity' => 1,
                ]
            ],
            'payment_method' => $this->mapPaymentMethod($req->method),
            'description' => 'Payment for ' . $req->item->getTitle(),
            'payment_expiry' => 3600, // 1 hour
        ];

        try {
            // Create payment request with Fawry
            $response = $this->client->createPaymentRequest($paymentData);

            if (!$response['statusCode'] || $response['statusCode'] !== 200) {
                throw new \RuntimeException('Fawry payment creation failed: ' . ($response['statusDescription'] ?? 'Unknown error'));
            }

            // Record payment in database
            $payment = $this->recorder->start($req, $response, 'fawry');

            // Prepare payment intent response
            $referenceNumber = $response['referenceNumber'] ?? null;
            $paymentUrl = $referenceNumber ? $this->client->getPaymentUrl($referenceNumber) : null;

            return new PaymentIntent(
                gateway: 'fawry',
                gatewayOrderId: (string) $response['merchantRefNum'],
                paymentKey: $referenceNumber ?? '',
                iframeUrl: $paymentUrl,
                raw: $response
            );

        } catch (\Exception $e) {
            Log::error('Fawry payment intent creation failed', [
                'error' => $e->getMessage(),
                'payment_data' => $paymentData,
                'request' => $req
            ]);

            throw new \RuntimeException('Failed to create Fawry payment intent: ' . $e->getMessage());
        }
    }

    public function captureWebhook(array $payload, array $headers): void
    {
        try {
            // Validate webhook signature
            $providedSignature = $payload['signature'] ?? null;
            
            if (!$providedSignature || !$this->client->verifyWebhookSignature($payload, $providedSignature)) {
                Log::warning('Fawry webhook signature verification failed', [
                    'payload' => $payload,
                    'headers' => $headers
                ]);
                
                throw new \RuntimeException('Invalid Fawry webhook signature');
            }

            // Extract payment information
            $merchantRefNum = $payload['merchantRefNum'] ?? null;
            $referenceNumber = $payload['referenceNumber'] ?? null;
            $statusCode = $payload['statusCode'] ?? null;
            $amount = $payload['amount'] ?? 0;

            if (!$merchantRefNum) {
                throw new \RuntimeException('Missing merchant reference number in Fawry webhook');
            }

            // Find payment record
            $payment = Payment::query()->where('merchant_order_id', $merchantRefNum)->firstOrFail();

            // Determine payment status
            $isSuccessful = $this->isPaymentSuccessful($statusCode);

            if ($isSuccessful) {
                $this->recorder->succeed($payment, $payload, $referenceNumber);
            } else {
                $this->recorder->fail($payment, $payload, $referenceNumber);
            }

        } catch (\Exception $e) {
            Log::error('Fawry webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
                'headers' => $headers
            ]);

            throw $e;
        }
    }

    /**
     * Map payment method enum to Fawry payment method
     */
    private function mapPaymentMethod(PaymentMethod $method): string
    {
        return match ($method) {
            PaymentMethod::CARD => 'CARD',
            PaymentMethod::WALLET => 'WALLET',
            default => 'PAYATFAWRY' // Default to Fawry points
        };
    }

    /**
     * Check if payment status indicates success
     */
    private function isPaymentSuccessful(?int $statusCode): bool
    {
        // Fawry success status codes
        $successCodes = [200, 201, 202];

        return in_array($statusCode, $successCodes);
    }

}
