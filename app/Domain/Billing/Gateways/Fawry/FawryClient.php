<?php

namespace App\Domain\Billing\Gateways\Fawry;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class FawryClient
{
    private string $baseUrl;
    private string $merchantCode;
    private string $securityKey;
    private string $merchantRefNum;

    public function __construct()
    {
        $this->baseUrl = config('services.fawry.base_url', 'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/');
        $this->merchantCode = config('services.fawry.merchant_code');
        $this->securityKey = config('services.fawry.security_key');
        $this->merchantRefNum = config('services.fawry.merchant_ref_num');
    }

    /**
     * Create a payment request with Fawry
     */
    public function createPaymentRequest(array $paymentData): array
    {
        $signature = $this->generateSignature($paymentData);

        $payload = [
            'merchantCode' => $this->merchantCode,
            'merchantRefNum' => $paymentData['merchant_ref_num'],
            'customerMobile' => $paymentData['customer_mobile'],
            'customerMail' => $paymentData['customer_email'],
            'customerProfileId' => $paymentData['customer_profile_id'] ?? null,
            'amount' => $paymentData['amount'],
            'currencyCode' => $paymentData['currency_code'] ?? 'EGP',
            'language' => $paymentData['language'] ?? 'en-gb',
            'chargeItems' => $paymentData['charge_items'],
            'signature' => $signature,
            'paymentMethod' => $paymentData['payment_method'] ?? 'PAYATFAWRY',
            'description' => $paymentData['description'] ?? 'Payment',
            'paymentExpiry' => $paymentData['payment_expiry'] ?? 3600, // 1 hour
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . 'charge', $payload);

            if (!$response->successful()) {
                Log::error('Fawry payment request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payload' => $payload
                ]);
                
                throw new \RuntimeException('Fawry payment request failed: ' . $response->body());
            }

            $data = $response->json();

            if (!$data['statusCode'] || $data['statusCode'] !== 200) {
                Log::error('Fawry payment request error', [
                    'status_code' => $data['statusCode'] ?? 'unknown',
                    'message' => $data['statusDescription'] ?? 'Unknown error',
                    'payload' => $payload
                ]);
                
                throw new \RuntimeException('Fawry payment error: ' . ($data['statusDescription'] ?? 'Unknown error'));
            }

            return $data;

        } catch (\Exception $e) {
            Log::error('Fawry API request exception', [
                'message' => $e->getMessage(),
                'payload' => $payload
            ]);
            
            throw new \RuntimeException('Fawry API request failed: ' . $e->getMessage());
        }
    }

    /**
     * Get payment status from Fawry
     */
    public function getPaymentStatus(string $merchantRefNum, string $referenceNumber): array
    {
        $signature = $this->generateStatusSignature($merchantRefNum, $referenceNumber);

        $payload = [
            'merchantCode' => $this->merchantCode,
            'merchantRefNum' => $merchantRefNum,
            'referenceNumber' => $referenceNumber,
            'signature' => $signature,
        ];

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . 'status', $payload);

            if (!$response->successful()) {
                Log::error('Fawry status request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payload' => $payload
                ]);
                
                throw new \RuntimeException('Fawry status request failed: ' . $response->body());
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Fawry status API exception', [
                'message' => $e->getMessage(),
                'payload' => $payload
            ]);
            
            throw new \RuntimeException('Fawry status API failed: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment webhook signature
     */
    public function verifyWebhookSignature(array $payload, string $providedSignature): bool
    {
        $expectedSignature = $this->generateWebhookSignature($payload);
        
        return hash_equals($expectedSignature, $providedSignature);
    }

    /**
     * Generate signature for payment request
     */
    private function generateSignature(array $paymentData): string
    {
        $signatureString = $this->merchantCode . 
                          $paymentData['merchant_ref_num'] . 
                          $paymentData['customer_mobile'] . 
                          $paymentData['customer_email'] . 
                          $paymentData['amount'] . 
                          $this->securityKey;

        return hash('sha256', $signatureString);
    }

    /**
     * Generate signature for status request
     */
    private function generateStatusSignature(string $merchantRefNum, string $referenceNumber): string
    {
        $signatureString = $this->merchantCode . $merchantRefNum . $referenceNumber . $this->securityKey;

        return hash('sha256', $signatureString);
    }

    /**
     * Generate signature for webhook verification
     */
    private function generateWebhookSignature(array $payload): string
    {
        $signatureString = $payload['merchantCode'] . 
                          $payload['merchantRefNum'] . 
                          $payload['referenceNumber'] . 
                          $payload['amount'] . 
                          $payload['statusCode'] . 
                          $this->securityKey;

        return hash('sha256', $signatureString);
    }

    /**
     * Get payment URL for redirect
     */
    public function getPaymentUrl(string $referenceNumber): string
    {
        return $this->baseUrl . 'pay/' . $referenceNumber;
    }

    /**
     * Validate required configuration
     */
    public function validateConfiguration(): bool
    {
        return !empty($this->merchantCode) && 
               !empty($this->securityKey) && 
               !empty($this->baseUrl);
    }
}
