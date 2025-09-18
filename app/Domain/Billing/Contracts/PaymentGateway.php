<?php

namespace App\Domain\Billing\Contracts;

use App\Domain\Billing\DTOs\PaymentRequest;
use App\Domain\Billing\DTOs\PaymentIntent;

interface PaymentGateway {
    public function createIntent(PaymentRequest $req): PaymentIntent;
    public function captureWebhook(array $payload, array $headers): void; // idempotent
}