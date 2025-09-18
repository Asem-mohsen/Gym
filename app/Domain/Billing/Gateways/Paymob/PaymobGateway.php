<?php

namespace App\Domain\Billing\Gateways\Paymob;

use App\Domain\Billing\PaymentRecorder;
use App\Domain\Billing\Contracts\PaymentGateway;
use App\Domain\Billing\DTOs\PaymentRequest;
use App\Domain\Billing\DTOs\PaymentIntent;
use App\Domain\Billing\Gateways\Paymob\PaymobClient;
use App\Domain\Billing\Gateways\Paymob\HmacVerifier;
use App\Enums\PaymentMethod;
use App\Models\Payment;

final class PaymobGateway implements PaymentGateway {
    public function __construct(
        private PaymobClient $client,
        private PaymentRecorder $recorder,
        private HmacVerifier $verifier
    ) {}

    public function createIntent(PaymentRequest $req): PaymentIntent
    {
        $token = $this->client->authenticate();
    
        // 1. Create order
        $order = $this->client->registerOrder($token, [
            'merchant_order_id' => $req->merchantOrderId,
            'amount_cents' => $req->item->getAmount() * 100,
            'currency' => $req->item->getCurrency(),
            'items' => [[
                'name' => $req->item->getTitle(),
                'amount_cents' => $req->item->getAmount() * 100,
                'quantity' => 1,
            ]],
            'shipping_data' => $req->billingData ?? [],
        ]);
    
        // 2. Build billing data with all required fields
        $billing = array_merge([
            'apartment'      => 'NA',
            'email'          => $req->billingData['email'] ?? 'no-reply@example.com',
            'floor'          => 'NA',
            'first_name'     => $req->billingData['first_name'] ?? 'Guest',
            'last_name'      => $req->billingData['last_name'] ?? 'User',
            'street'         => $req->billingData['street'] ?? 'NA',
            'building'       => $req->billingData['building'] ?? 'NA',
            'phone_number'   => $req->billingData['phone_number'] ?? '0000000000',
            'shipping_method'=> 'PKG',
            'postal_code'    => 'NA',
            'city'           => 'NA',
            'country'        => 'EG',
            'state'          => 'NA',
            'user_id'        => $req->user->id,
            'site_setting_id' => $req->billingData['site_setting_id'] ?? null,
        ], $req->billingData ?? []);
    
        // 3. Create payment key
        $paymentKey = $this->client->paymentKey($token, [
            'amount_cents'  => $req->item->getAmount() * 100,
            'currency'      => $req->item->getCurrency(),
            'order_id'      => $order['id'],
            'billing_data'  => $billing,
            'integration_id'=> config('services.paymob.integrations.card'),
            'expiration'    => 3600,
        ]);
    
        $payment = $this->recorder->start($req, $order);
    
        if ($req->method === PaymentMethod::CARD) {
            $iframeId = config('services.paymob.iframe_id');
            $iframeUrl = sprintf(
                'https://accept.paymob.com/api/acceptance/iframes/%s?payment_token=%s',
                $iframeId,
                $paymentKey['token']
            );
    
            return new PaymentIntent('paymob', (string)$order['id'], $paymentKey['token'], $iframeUrl, null, ['order' => $order]);
        }
    
        if ($req->method === PaymentMethod::WALLET) {
            return new PaymentIntent('paymob', (string)$order['id'], $paymentKey['token'], null, null, ['order' => $order]);
        }
    
        throw new \RuntimeException('Unsupported method');
    }
    
    
    public function captureWebhook(array $payload, array $headers): void
    {
        $provided = $payload['hmac'] ?? $headers['hmac'] ?? null;
        if (!$provided || !$this->verifier->verifyProcessed($payload, $provided))
            abort(400, 'Invalid HMAC');
    
        $merchantOrderId = data_get($payload, 'order.merchant_order_id') ?? data_get($payload, 'merchant_order_id');
        $payment = Payment::query()->where('merchant_order_id', $merchantOrderId)->firstOrFail();
    
        $success = (bool)($payload['success'] ?? false);
        $txnId   = (string)($payload['id'] ?? '');
    
        $success
            ? $this->recorder->succeed($payment, $payload, $txnId)
            : $this->recorder->fail($payment, $payload, $txnId);
    }
}