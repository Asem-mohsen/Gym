<?php

namespace App\Http\Controllers\Webhooks;

use App\Domain\Billing\PaymentGatewayManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function __construct(
        private PaymentGatewayManager $gatewayManager
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $payload = $request->all();
            $headers = $request->headers->all();

            Log::info('Stripe webhook received', [
                'event_type' => $payload['type'] ?? 'unknown',
                'payload_id' => $payload['id'] ?? 'unknown'
            ]);

            // Get Stripe gateway and process webhook
            $stripeGateway = $this->gatewayManager->getGatewayByName('stripe');
            $stripeGateway->captureWebhook($payload, $headers);

            return response('Webhook processed successfully', 200);

        } catch (\Exception $e) {
            Log::error('Stripe webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            return response('Webhook processing failed', 500);
        }
    }
}
