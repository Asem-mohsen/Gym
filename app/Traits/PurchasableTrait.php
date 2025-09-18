<?php

namespace App\Traits;

trait PurchasableTrait
{
    public function getPurchasableId(): int|string
    {
        return $this->id;
    }

    public function getPurchasableType(): string
    {
        return class_basename($this);
    }

    public function markPaid(string $gatewayRef, array $payload): void
    {
        $this->update(['status' => 'paid']);
    }

    public function markFailed(array $payload): void
    {
        $this->update(['status' => 'failed']);
    }
}
