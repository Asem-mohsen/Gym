<?php

namespace App\Domain\Billing;

use App\Domain\Billing\Contracts\PaymentGateway;
use App\Domain\Billing\Gateways\Paymob\PaymobGateway;
use App\Domain\Billing\Gateways\Stripe\StripeGateway;
use App\Domain\Billing\Gateways\Fawry\FawryGateway;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\App;

final class PaymentGatewayManager
{
    private array $gateways = [];

    public function __construct(
        private PaymobGateway $paymobGateway,
        private StripeGateway $stripeGateway,
        private FawryGateway $fawryGateway
    ) {
        $this->gateways = [
            'paymob' => $this->paymobGateway,
            'stripe' => $this->stripeGateway,
            'fawry' => $this->fawryGateway,
        ];
    }

    /**
     * Get the appropriate gateway based on site settings
     */
    public function getGateway(?SiteSetting $siteSetting = null): PaymentGateway
    {
        $gatewayName = $this->getGatewayName($siteSetting);
        
        if (!isset($this->gateways[$gatewayName])) {
            throw new \RuntimeException("Payment gateway '{$gatewayName}' is not supported");
        }

        return $this->gateways[$gatewayName];
    }

    /**
     * Get gateway by name
     */
    public function getGatewayByName(string $gatewayName): PaymentGateway
    {
        if (!isset($this->gateways[$gatewayName])) {
            throw new \RuntimeException("Payment gateway '{$gatewayName}' is not supported");
        }

        return $this->gateways[$gatewayName];
    }

    /**
     * Get all available gateways
     */
    public function getAvailableGateways(): array
    {
        return array_keys($this->gateways);
    }

    /**
     * Check if a gateway is supported
     */
    public function isGatewaySupported(string $gatewayName): bool
    {
        return isset($this->gateways[$gatewayName]);
    }

    /**
     * Get the gateway name from site settings or default
     */
    private function getGatewayName(?SiteSetting $siteSetting = null): string
    {
        if ($siteSetting && $siteSetting->payment_gateway) {
            return $siteSetting->payment_gateway;
        }

        return config('services.default_payment_gateway', 'paymob');
    }

    /**
     * Register a custom gateway
     */
    public function registerGateway(string $name, PaymentGateway $gateway): void
    {
        $this->gateways[$name] = $gateway;
    }

    /**
     * Get gateway configuration for frontend
     */
    public function getGatewayConfig(string $gatewayName): array
    {
        return match ($gatewayName) {
            'stripe' => [
                'public_key' => config('services.stripe.public_key'),
                'gateway_name' => 'stripe',
            ],
            'paymob' => [
                'iframe_id' => config('services.paymob.iframe_id'),
                'gateway_name' => 'paymob',
            ],
            'fawry' => [
                'merchant_code' => config('services.fawry.merchant_code'),
                'gateway_name' => 'fawry',
                'base_url' => config('services.fawry.base_url'),
            ],
            default => throw new \RuntimeException("Unknown gateway: {$gatewayName}")
        };
    }

    /**
     * Get the default gateway name
     */
    public function getDefaultGateway(): string
    {
        return config('services.default_payment_gateway', 'paymob');
    }
}
