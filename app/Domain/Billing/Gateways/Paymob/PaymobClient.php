<?php
namespace App\Domain\Billing\Gateways\Paymob;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Factory;

final class PaymobClient {
    public function __construct(private Factory $http) {}
    private function base(): PendingRequest 
    {
        return $this->http->baseUrl(config('services.paymob.base_url'))->acceptJson()->asJson();
    }

    public function authenticate(): string {
        $apiKey = config('services.paymob.api_key');
        return $this->base()->post('/auth/tokens', ['api_key' => $apiKey])->throw()->json('token');
    }

    public function registerOrder(string $token, array $payload): array {
        return $this->base()->withToken($token)->post('/ecommerce/orders', $payload)->throw()->json();
    }

    public function paymentKey(string $token, array $payload): array {
        return $this->base()->withToken($token)->post('/acceptance/payment_keys', $payload)->throw()->json();
    }

    public function walletPayUrl(string $paymentToken, string $walletNumber): array {
        return $this->base()->post('/acceptance/payments/pay', [
            'source' => ['identifier' => $walletNumber, 'subtype' => 'WALLET'],
            'payment_token' => $paymentToken,
        ])->throw()->json();
    }
}
