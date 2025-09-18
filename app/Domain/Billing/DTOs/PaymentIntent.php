<?php

namespace App\Domain\Billing\DTOs;

final class PaymentIntent {
    public function __construct(
        public readonly string $gateway='paymob',
        public readonly string $gatewayOrderId,
        public readonly ?string $paymentKey=null,
        public readonly ?string $iframeUrl=null,
        public readonly ?string $walletRedirectUrl=null,
        public readonly array $raw=[]
    ) {}
}