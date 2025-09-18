<?php

namespace App\Http\Controllers\Webhooks;

use App\Domain\Billing\Gateways\Paymob\PaymobGateway;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

final class PaymobWebhookController extends Controller {
    public function __construct(private PaymobGateway $gateway) {}
    public function handle(Request $r) {
        $this->gateway->captureWebhook($r->all(), $r->headers->all());
        return response()->json(['ok' => true]);
    }
}