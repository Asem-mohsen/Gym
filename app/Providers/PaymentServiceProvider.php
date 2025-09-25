<?php

namespace App\Providers;

use App\Domain\Billing\PaymentGatewayManager;
use App\Domain\Billing\PaymentRecorder;
use App\Domain\Billing\Gateways\Paymob\PaymobClient;
use App\Domain\Billing\Gateways\Paymob\PaymobGateway;
use App\Domain\Billing\Gateways\Paymob\HmacVerifier;
use App\Domain\Billing\Gateways\Stripe\StripeClient;
use App\Domain\Billing\Gateways\Stripe\StripeGateway;
use App\Domain\Billing\Gateways\Fawry\FawryClient;
use App\Domain\Billing\Gateways\Fawry\FawryGateway;
use App\Domain\Billing\Gateways\Fawry\FawryService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Paymob dependencies
        $this->app->singleton(PaymobClient::class);
        $this->app->singleton(HmacVerifier::class);
        $this->app->singleton(PaymobGateway::class);

        // Register Stripe dependencies
        $this->app->singleton(StripeClient::class);
        $this->app->singleton(StripeGateway::class);

        // Register Fawry dependencies
        $this->app->singleton(FawryClient::class);
        $this->app->singleton(FawryGateway::class);
        $this->app->singleton(FawryService::class);

        // Register PaymentRecorder
        $this->app->singleton(PaymentRecorder::class);

        // Register PaymentGatewayManager
        $this->app->singleton(PaymentGatewayManager::class, function ($app) {
            return new PaymentGatewayManager(
                $app->make(PaymobGateway::class),
                $app->make(StripeGateway::class),
                $app->make(FawryGateway::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
