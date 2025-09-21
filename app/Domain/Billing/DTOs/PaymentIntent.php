<?php

namespace App\Domain\Billing\DTOs;

final class PaymentIntent {
    public function __construct(
        public readonly string $gateway = 'paymob',
        public readonly string $gatewayOrderId,
        public readonly ?string $paymentKey = null,
        public readonly ?string $iframeUrl = null,
        public readonly ?string $walletRedirectUrl = null,
        public readonly ?string $checkoutUrl = null,
        public readonly ?string $clientSecret = null,
        public readonly array $raw = []
    ) {}

    /**
     * Get the appropriate payment URL based on gateway type
     */
    public function getPaymentUrl(): ?string
    {
        return match ($this->gateway) {
            'paymob' => $this->iframeUrl ?? $this->walletRedirectUrl,
            'stripe' => $this->checkoutUrl ?? $this->iframeUrl,
            default => $this->iframeUrl
        };
    }

    /**
     * Check if this is a hosted payment (redirect to external page)
     */
    public function isHostedPayment(): bool
    {
        return !empty($this->getPaymentUrl());
    }

    /**
     * Get frontend configuration for payment
     */
    public function getFrontendConfig(): array
    {
        return [
            'gateway' => $this->gateway,
            'gatewayOrderId' => $this->gatewayOrderId,
            'paymentUrl' => $this->getPaymentUrl(),
            'clientSecret' => $this->clientSecret,
            'paymentKey' => $this->paymentKey,
            'isHostedPayment' => $this->isHostedPayment(),
        ];
    }
}