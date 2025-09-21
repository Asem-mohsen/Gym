<?php

namespace App\Domain\Billing\Gateways\Stripe;

use App\Domain\Billing\PaymentRecorder;
use App\Domain\Billing\Contracts\PaymentGateway;
use App\Domain\Billing\DTOs\PaymentRequest;
use App\Domain\Billing\DTOs\PaymentIntent;
use App\Enums\PaymentMethod;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

final class StripeGateway implements PaymentGateway
{
    public function __construct(
        private StripeClient $client,
        private PaymentRecorder $recorder
    ) {}

    public function createIntent(PaymentRequest $req): PaymentIntent
    {
        try {
            $paymentMethodTypes = $this->getPaymentMethodTypes($req->method);

            $paymentIntentParams = [
                'amount' => $req->item->getAmount() * 100, 
                'currency' => strtolower($req->item->getCurrency()),
                'metadata' => array_merge($req->item->getMetadata(), [
                    'merchant_order_id' => $req->merchantOrderId,
                    'user_id' => $req->user?->id,
                    'site_setting_id' => $req->user?->getCurrentSite()?->id,
                ]),
            ];

            foreach ($paymentMethodTypes as $index => $type) {
                $paymentIntentParams["payment_method_types[{$index}]"] = $type;
            }

            if ($req->user) {
                $paymentIntentParams['receipt_email'] = $req->user->email;
            }

            $paymentIntent = $this->client->createPaymentIntent($paymentIntentParams);

            $payment = $this->recorder->start($req, $paymentIntent, 'stripe');

            $iframeUrl = null;
            $walletRedirectUrl = null;

            if ($req->method === PaymentMethod::CARD) {
                $iframeUrl = $this->generateCheckoutUrl($paymentIntent, $req);
            }

            return new PaymentIntent(
                gateway: 'stripe',
                gatewayOrderId: $paymentIntent['id'],
                paymentKey: $paymentIntent['client_secret'],
                iframeUrl: $iframeUrl,
                walletRedirectUrl: $walletRedirectUrl,
                checkoutUrl: $iframeUrl,
                clientSecret: $paymentIntent['client_secret'],
                raw: $paymentIntent
            );

        } catch (\Exception $e) {
            Log::error('Stripe payment intent creation failed', [
                'error' => $e->getMessage(),
                'payment_request' => $req->item->getMetadata(),
                'merchant_order_id' => $req->merchantOrderId
            ]);
            
            throw new \RuntimeException('Failed to create Stripe payment: ' . $e->getMessage());
        }
    }

    public function captureWebhook(array $payload, array $headers): void
    {
        try {
            // Verify webhook signature
            $signature = $headers['stripe-signature'][0] ?? '';
            $endpointSecret = config('services.stripe.webhook_secret');
            
            if (!$this->client->verifyWebhookSignature(json_encode($payload), $signature, $endpointSecret)) {
                Log::warning('Stripe webhook signature verification failed', [
                    'signature' => $signature,
                    'payload' => $payload
                ]);
                return;
            }

            $eventType = $payload['type'] ?? '';
            $paymentIntentId = $payload['data']['object']['id'] ?? '';

            // Handle different event types
            switch ($eventType) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSuccess($paymentIntentId, $payload);
                    break;
                    
                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailure($paymentIntentId, $payload);
                    break;
                    
                default:
                    Log::info('Unhandled Stripe webhook event', [
                        'event_type' => $eventType,
                        'payment_intent_id' => $paymentIntentId
                    ]);
            }

        } catch (\Exception $e) {
            Log::error('Stripe webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
                'headers' => $headers
            ]);
        }
    }

    private function getPaymentMethodTypes(PaymentMethod $method): array
    {
        return match ($method) {
            PaymentMethod::CARD => ['card'],
            PaymentMethod::WALLET => ['card'], // Stripe doesn't support mobile wallets in Egypt
            default => ['card']
        };
    }

    private function generateCheckoutUrl(array $paymentIntent, PaymentRequest $req): string
    {
        try {
            // Create a Stripe Checkout session for better UX
            $sessionParams = [
                'payment_method_types[]' => 'card',
                'line_items[0][price_data][currency]' => strtolower($req->item->getCurrency()),
                'line_items[0][price_data][product_data][name]' => $req->item->getTitle(),
                'line_items[0][price_data][unit_amount]' => $req->item->getAmount() * 100,
                'line_items[0][quantity]' => 1,
                'mode' => 'payment',
                'success_url' => route('user.payment.stripe.return', ['siteSetting' => $req->user->getCurrentSite()->slug]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('user.payment.stripe.cancel', ['siteSetting' => $req->user->getCurrentSite()->slug]),
                'payment_intent_data[metadata][merchant_order_id]' => $req->merchantOrderId,
                'payment_intent_data[metadata][user_id]' => $req->user?->id,
            ];

            // Add customer email if available
            if ($req->user?->email) {
                $sessionParams['customer_email'] = $req->user->email;
            }

            $session = $this->client->createCheckoutSession($sessionParams);
            
            return $session['url'];

        } catch (\Exception $e) {
            Log::error('Failed to create Stripe checkout session', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntent['id']
            ]);
            
            // Fallback to direct payment intent
            return route('user.payment.stripe', ['payment_intent' => $paymentIntent['id']]);
        }
    }

    private function handlePaymentSuccess(string $paymentIntentId, array $payload): void
    {
        try {
            $paymentIntent = $payload['data']['object'];
            $merchantOrderId = $paymentIntent['metadata']['merchant_order_id'] ?? '';

            if (!$merchantOrderId) {
                Log::warning('Stripe payment success without merchant_order_id', [
                    'payment_intent_id' => $paymentIntentId
                ]);
                return;
            }

            $payment = Payment::query()
                ->where('merchant_order_id', $merchantOrderId)
                ->first();

            if (!$payment) {
                Log::warning('Stripe payment success but payment not found', [
                    'payment_intent_id' => $paymentIntentId,
                    'merchant_order_id' => $merchantOrderId
                ]);
                return;
            }

            $this->recorder->succeed($payment, $payload, $paymentIntentId);

        } catch (\Exception $e) {
            Log::error('Stripe payment success handling failed', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId,
                'payload' => $payload
            ]);
        }
    }

    private function handlePaymentFailure(string $paymentIntentId, array $payload): void
    {
        try {
            $paymentIntent = $payload['data']['object'];
            $merchantOrderId = $paymentIntent['metadata']['merchant_order_id'] ?? '';

            if (!$merchantOrderId) {
                Log::warning('Stripe payment failure without merchant_order_id', [
                    'payment_intent_id' => $paymentIntentId
                ]);
                return;
            }

            $payment = Payment::query()
                ->where('merchant_order_id', $merchantOrderId)
                ->first();

            if (!$payment) {
                Log::warning('Stripe payment failure but payment not found', [
                    'payment_intent_id' => $paymentIntentId,
                    'merchant_order_id' => $merchantOrderId
                ]);
                return;
            }

            $this->recorder->fail($payment, $payload, $paymentIntentId);

        } catch (\Exception $e) {
            Log::error('Stripe payment failure handling failed', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId,
                'payload' => $payload
            ]);
        }
    }
}
