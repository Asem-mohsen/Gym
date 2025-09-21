<?php

namespace App\Http\Controllers\Webhooks;

use App\Domain\Billing\Gateways\Paymob\PaymobGateway;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class PaymobWebhookController extends Controller {
    public function __construct(private PaymobGateway $gateway) {}

    public function handle(Request $request)
    {
        $payload = $request->all();

        $headers = $request->headers->all();

        $this->gateway->captureWebhook($payload, $headers);

        return response()->json(['ok' => true]);
    }
}