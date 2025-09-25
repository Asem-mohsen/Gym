<?php

namespace App\Domain\Billing\Gateways\Stripe;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class StripeClient
{
    private string $baseUrl = 'https://api.stripe.com/v1';
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.stripe.secret_key');
        
        if (!$this->secretKey) {
            throw new \RuntimeException('Stripe secret key not configured');
        }
    }

    /**
     * Create a payment intent with Stripe
     */
    public function createPaymentIntent(array $params): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->asForm()
            ->post("{$this->baseUrl}/payment_intents", $params);

        if (!$response->successful()) {
            Log::error('Stripe payment intent creation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'params' => $params
            ]);
            
            throw new \RuntimeException('Failed to create Stripe payment intent: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Retrieve a payment intent
     */
    public function retrievePaymentIntent(string $paymentIntentId): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->get("{$this->baseUrl}/payment_intents/{$paymentIntentId}");

        if (!$response->successful()) {
            Log::error('Stripe payment intent retrieval failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payment_intent_id' => $paymentIntentId
            ]);
            
            throw new \RuntimeException('Failed to retrieve Stripe payment intent');
        }

        return $response->json();
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $payload, string $signature, string $endpointSecret): bool
    {
        $timestamp = null;
        $v1Signature = null;

        // Parse the signature header
        foreach (explode(',', $signature) as $pair) {
            [$key, $value] = explode('=', $pair, 2);
            if ($key === 't') {
                $timestamp = $value;
            } elseif ($key === 'v1') {
                $v1Signature = $value;
            }
        }

        if (!$timestamp || !$v1Signature) {
            return false;
        }

        // Verify timestamp (within 5 minutes)
        if (time() - $timestamp > 300) {
            Log::warning('Stripe webhook timestamp too old', ['timestamp' => $timestamp]);
            return false;
        }

        // Compute expected signature
        $signedPayload = $timestamp . '.' . $payload;
        $expectedSignature = hash_hmac('sha256', $signedPayload, $endpointSecret);

        return hash_equals($expectedSignature, $v1Signature);
    }

    /**
     * Create a checkout session for hosted payments
     */
    public function createCheckoutSession(array $params): array
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->asForm()
            ->post("{$this->baseUrl}/checkout/sessions", $params);

        if (!$response->successful()) {
            Log::error('Stripe checkout session creation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'params' => $params
            ]);
            
            throw new \RuntimeException('Failed to create Stripe checkout session: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey(): string
    {
        return config('services.stripe.public_key', '');
    }
}
