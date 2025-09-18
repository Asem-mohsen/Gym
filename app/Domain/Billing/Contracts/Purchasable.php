<?php

namespace App\Domain\Billing\Contracts;

interface Purchasable
{
    public function getPurchasableType(): string;          // 'membership' | 'class' | 'service' | ...
    public function getPurchasableId(): int|string;
    public function getTitle(): string;                    // For receipts
    public function getAmount(): int;                 // always cents (integer)
    public function getCurrency(): string;                 // 'EGP', 'AED', ...
    public function getCustomerEmail(): ?string;
    public function getCustomerPhone(): ?string;
    public function getMetadata(): array;                  // anything you want echoed back in webhook
    public function markPaid(string $gatewayRef, array $payload): void;
    public function markFailed(array $payload): void;
}
