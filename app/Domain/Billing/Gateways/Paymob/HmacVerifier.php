<?php 

namespace App\Domain\Billing\Gateways\Paymob;

final class HmacVerifier {
    public function verifyProcessed(array $payload, string $providedHmac): bool
    {
        $secret = config('services.paymob.hmac');

        $ordered = [
            'amount_cents','created_at','currency','error_occured','has_parent_transaction','id',
            'integration_id','is_3d_secure','is_auth','is_capture','is_refunded','is_standalone_payment',
            'is_voided','order.id','owner','pending','source_data.pan','source_data.sub_type','source_data.type',
            'success'
        ];
        $base = '';
        foreach ($ordered as $key) { $base .= data_get($payload, $key, ''); }
        $calc = hash_hmac('sha512', $base, $secret); // Paymob docs specify algo; often sha512
        return hash_equals($calc, $providedHmac);
    }

    public function verifyRedirection(array $query, string $providedHmac): bool
    {
        $secret = config('services.paymob.hmac');
        $ordered = [
            'amount_cents','created_at','currency','error_occured','has_parent_transaction','id',
            'integration_id','is_3d_secure','is_auth','is_capture','is_refunded','is_standalone_payment',
            'is_voided','order','owner','pending','source_data_pan','source_data_sub_type','source_data_type',
            'success'
        ];
        $base=''; foreach ($ordered as $k) { $base .= $query[$k] ?? ''; }
        $calc = hash_hmac('sha512', $base, $secret);
        return hash_equals($calc, $providedHmac);
    }
}
