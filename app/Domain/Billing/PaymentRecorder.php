<?php

namespace App\Domain\Billing;

use App\Models\Payment;
use App\Domain\Billing\DTOs\PaymentRequest;
use Illuminate\Support\Facades\DB;
use App\Domain\Billing\Contracts\Purchasable;

final class PaymentRecorder {
    
    public function start(PaymentRequest $req, array $gatewayOrder, string $gatewayName = 'paymob'): Payment {
        return DB::transaction(function () use ($req, $gatewayOrder, $gatewayName) {
            $payment = Payment::query()->firstOrCreate(
                ['merchant_order_id' => $req->merchantOrderId],
                [
                    'paymentable_type' => get_class($req->item),
                    'paymentable_id'   => $req->item->getPurchasableId(),
                    'amount'       => $req->item->getAmount(),
                    'currency'     => $req->item->getCurrency(),
                    'gateway'      => $gatewayName,
                    'gateway_order_id' => $gatewayOrder['id'] ?? null,
                    'meta'           => $req->item->getMetadata(),
                    'user_id'        => $req->user->id,
                    'payment_method' => $req->method->value,
                    'site_setting_id' => $req->user->getCurrentSite()->id,
                ]
            );

            $payment->attempts()->create([
                'method' => $req->method->value,
                'gateway_transaction_id' => $gatewayOrder['id'] ?? null,
                'gateway_integration_id' => $gatewayOrder['integration_id'] ?? null,
                'status'  => 'pending',
                'gateway' => $gatewayName,
                'response' => $gatewayOrder,
            ]);

            return $payment;
        });
    }

    public function succeed(Payment $payment, array $payload, ?string $transactionId): void {
        DB::transaction(function () use ($payment, $payload, $transactionId) {
            $payment->update(['status' => 'succeeded']);
            $payment->attempts()->latest()->update([
                'status' => 'succeeded',
                'gateway_transaction_id' => $transactionId,
                'gateway_integration_id' => $payload['integration_id'] ?? null,
                'response' => $payload,
            ]);
            /** @var Purchasable $item */
            $item = $payment->payable;
            $item->markPaid((string)$transactionId, $payload);
        });
    }

    public function fail(Payment $payment, array $payload, ?string $transactionId): void {
        DB::transaction(function () use ($payment, $payload, $transactionId) {
            $payment->update(['status' => 'failed']);
            $payment->attempts()->latest()->update([
                'status' => 'failed',
                'gateway_transaction_id' => $transactionId,
                'gateway_integration_id' => $payload['integration_id'] ?? null,
                'response' => $payload,
            ]);
            /** @var Purchasable $item */
            $item = $payment->payable;
            $item->markFailed($payload);
        });
    }
}