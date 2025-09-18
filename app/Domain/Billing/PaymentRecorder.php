<?php

namespace App\Domain\Billing;

use App\Models\Payment;
use App\Domain\Billing\DTOs\PaymentRequest;
use Illuminate\Support\Facades\DB;
use App\Domain\Billing\Contracts\Purchasable;

final class PaymentRecorder {
    public function start(PaymentRequest $req, array $gatewayOrder): Payment {
        return DB::transaction(function () use ($req, $gatewayOrder) {
            $payment = Payment::query()->firstOrCreate(
                ['merchant_order_id' => $req->merchantOrderId],
                [
                    'paymentable_type' => get_class($req->item),
                    'paymentable_id'   => $req->item->getPurchasableId(),
                    'amount'       => $req->item->getAmount(),
                    'currency'     => $req->item->getCurrency(),
                    'gateway'      => 'paymob',
                    'gateway_order_id' => $gatewayOrder['id'] ?? null,
                    'meta'           => $req->item->getMetadata(),
                    'user_id'        => $req->user->id,
                    'payment_method' => $req->method->value,
                    'site_setting_id' => $req->billingData['site_setting_id'],
                ]
            );

            $payment->attempts()->create([
                'method' => $req->method->value,
                'gateway_transaction_id' => $gatewayOrder['id'] ?? null,
                'gateway_integration_id' => $gatewayOrder['integration_id'] ?? null,
                'status' => 'pending',
                'gatway' => 'paymob',
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
                'paymob_transaction_id' => $transactionId,
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
                'paymob_transaction_id' => $transactionId,
                'response' => $payload,
            ]);
            /** @var Purchasable $item */
            $item = $payment->payable;
            $item->markFailed($payload);
        });
    }
}