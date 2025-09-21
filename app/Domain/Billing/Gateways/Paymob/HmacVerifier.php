<?php 

namespace App\Domain\Billing\Gateways\Paymob;

final class HmacVerifier {

    private array $processedKeys = [
        'obj.amount_cents',
        'obj.created_at',
        'obj.currency',
        'obj.error_occured',
        'obj.has_parent_transaction',
        'obj.id',
        'obj.integration_id',
        'obj.is_3d_secure',
        'obj.is_auth',
        'obj.is_capture',
        'obj.is_refunded',
        'obj.is_standalone_payment',
        'obj.is_voided',
        'obj.order.id',
        'obj.owner',
        'obj.pending',
        'obj.source_data.pan',
        'obj.source_data.sub_type',
        'obj.source_data.type',
        'obj.success',
    ];

    public function verifyProcessed(array $payload, string $providedHmac): bool
    {
        $secret = (string) config('services.paymob.hmac');

        $base = '';
        foreach ($this->processedKeys as $key) {
            $val = data_get($payload, $key, '');
            $base .= $this->normalize($val);
        }

        $calc = hash_hmac('sha512', $base, $secret);

        return hash_equals($calc, $providedHmac);
    }


    public function verifyRedirection(array $query, string $providedHmac): bool
    {
        $secret = config('services.paymob.hmac');

        $orderedKeys = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];

        $base = '';
        foreach ($orderedKeys as $key) {
            $base .= $query[$key] ?? '';
        }

        $calc = hash_hmac('sha512', $base, $secret);

        return hash_equals($calc, $providedHmac);
    }

    private function normalize($val): string
    {
        if (is_bool($val)) {
            return $val ? 'true' : 'false';
        }
        if ($val === null) {
            return '';
        }
        return (string) $val;
    }
}
