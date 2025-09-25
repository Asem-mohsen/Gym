<?php

namespace App\Http\Controllers\Webhooks;

use App\Domain\Billing\Gateways\Fawry\FawryGateway;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class FawryWebhookController extends Controller
{
    public function __construct(private FawryGateway $gateway) {}

    /**
     * Handle Fawry payment webhook notifications
     */
    public function handle(Request $request)
    {
        try {
            $payload = $request->all();
            $headers = $request->headers->all();

            Log::info('Fawry webhook received', [
                'payload' => $payload,
                'headers' => $headers
            ]);

            // Process the webhook through the gateway
            $this->gateway->captureWebhook($payload, $headers);

            Log::info('Fawry webhook processed successfully', [
                'merchant_ref_num' => $payload['merchantRefNum'] ?? 'unknown'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Webhook processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Fawry webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Webhook processing failed'
            ], 500);
        }
    }

    /**
     * Handle Fawry payment status callback
     */
    public function statusCallback(Request $request)
    {
        try {
            $payload = $request->all();
            $headers = $request->headers->all();

            Log::info('Fawry status callback received', [
                'payload' => $payload,
                'headers' => $headers
            ]);

            // Process the status callback
            $this->gateway->captureWebhook($payload, $headers);

            return response()->json([
                'status' => 'success',
                'message' => 'Status callback processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Fawry status callback processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Status callback processing failed'
            ], 500);
        }
    }
}
