<?php

namespace App\Domain\Billing\DTOs;

use App\Domain\Billing\Contracts\Purchasable;
use App\Enums\PaymentMethod;
use App\Models\User;

final class PaymentRequest {
    public function __construct(
        public readonly Purchasable $item,
        public readonly PaymentMethod $method,
        public readonly string $merchantOrderId,  // your idempotent unique ref
        public readonly ?string $returnUrl = null, // for web redirection
        public readonly ?array $billingData = null, // name, phone, email...
        public readonly ?User $user = null // user who is paying
    ) {}
}