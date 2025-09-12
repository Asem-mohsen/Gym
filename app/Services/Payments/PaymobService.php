<?php

namespace App\Services\Payments;

use Exception;
use App\Models\{ Membership, Offer, User, ClassModel, Booking};
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymobService
{
    private string $apiKey;
    private string $integrationId;
    private string $iframeId;
    private string $hmacSecret;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->integrationId = config('services.paymob.integration_id');
        $this->iframeId = config('services.paymob.iframe_id');
        $this->hmacSecret = config('services.paymob.hmac_secret');
        $this->baseUrl = config('services.paymob.base_url');
    }

    /**
     * Step 1: Get authentication token
     * https://docs.paymob.com/docs/card-payments#step-1-get-authentication-token
     */
    public function getAuthToken(): ?string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/auth/tokens', [
                'api_key' => $this->apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['token'] ?? null;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Paymob authentication exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Step 2: Create order
     * https://docs.paymob.com/docs/card-payments#step-2-create-order
     */
    public function createOrder(string $authToken, Membership $membership, User $user, ?Offer $offer = null): ?array
    {
        try {
            $finalPrice = $this->calculateFinalPrice($membership, $offer);
            $amountCents = (int)($finalPrice * 100);

            $orderData = [
                'auth_token' => $authToken,
                'delivery_needed' => false,
                'amount_cents' => $amountCents,
                'currency' => 'EGP',
                'items' => [
                    [
                        'name' => $membership->name,
                        'amount_cents' => $amountCents,
                        'description' => $membership->general_description ?? 'Gym Membership',
                        'quantity' => 1
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/ecommerce/orders', $orderData);

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Paymob order creation exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Step 3: Create payment key
     * https://docs.paymob.com/docs/card-payments#step-3-create-payment-key
     */
    public function createPaymentKey(string $authToken, array $orderData, Membership $membership, User $user, ?Offer $offer = null): ?array
    {
        try {
            $finalPrice = $this->calculateFinalPrice($membership, $offer);
            $amountCents = (int)($finalPrice * 100);

            $nameParts = explode(' ', trim($user->name), 2);
            $firstName = $nameParts[0] ?? 'User';
            $lastName = $nameParts[1] ?? 'Name';
            
            $email = $user->email;
            $phone = $user->phone ?? '+201234567890';
            $address = $user->address ?? 'Cairo, Egypt';
            $city = 'Cairo';

            $paymentKeyData = [
                'auth_token' => $authToken,
                'amount_cents' => $amountCents,
                'expiration' => 3600, // 1 hour
                'order_id' => $orderData['id'],
                'billing_data' => [
                    'apartment' => 'NA',
                    'email' => $email,
                    'floor' => 'NA',
                    'first_name' => $firstName,
                    'street' => $address,
                    'building' => 'NA',
                    'phone_number' => $phone,
                    'shipping_method' => 'NA',
                    'postal_code' => 'NA',
                    'city' => $city,
                    'country' => 'EG',
                    'last_name' => $lastName,
                    'state' => 'NA'
                ],
                'currency' => 'EGP',
                'integration_id' => (int)$this->integrationId,
                'lock_order_when_paid' => false,
                'success_url' => route('payments.paymob.callback'),
                'failure_url' => route('payments.paymob.callback'),
                'items' => [
                    [
                        'name' => $membership->name,
                        'amount_cents' => $amountCents,
                        'description' => $membership->general_description ?? 'Gym Membership',
                        'quantity' => 1
                    ]
                ]
            ];

            $response = Http::withHeaders(['Content-Type' => 'application/json',])->timeout(30)->post($this->baseUrl . '/acceptance/payment_keys', $paymentKeyData);

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Paymob payment key creation exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $orderData['id'] ?? 'unknown'
            ]);
            return null;
        }
    }

    /**
     * Get iframe URL for payment
     * https://docs.paymob.com/docs/card-payments#step-4-redirect-to-payment-page
     */
    public function getIframeUrl(string $paymentKey): string
    {
        return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";
    }

    /**
     * Verify payment callback using HMAC
     * https://docs.paymob.com/docs/card-payments#step-5-verify-payment
     */
    public function verifyPayment(array $callbackData): bool
    {
        $calculatedHmac = $this->calculateHmac($callbackData);
        $receivedHmac = $callbackData['hmac'] ?? '';

        if (hash_equals($calculatedHmac, $receivedHmac)) {
            return true;
        }

        return false;
    }

    /**
     * Calculate HMAC for payment verification
     * https://docs.paymob.com/docs/card-payments#hmac-calculation
     */
    private function calculateHmac(array $data): string
    {
        // Remove hmac from data before processing
        unset($data['hmac']);
        
        // Define the exact order of keys as per Paymob documentation
        $requiredKeys = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'obj.id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order.id',
            'owner',
            'pending',
            'source_data.pan',
            'source_data.sub_type',
            'source_data.type',
            'success'
        ];
        
        $keyMapping = [
            'id' => 'obj.id',
            'order' => 'order.id',
            'source_data_pan' => 'source_data.pan',
            'source_data_sub_type' => 'source_data.sub_type',
            'source_data_type' => 'source_data.type'
        ];
        
        // Build the HMAC data in the exact required order
        $hmacData = [];
        foreach ($requiredKeys as $key) {
            // Check if we have a mapping for this key
            $sourceKey = array_search($key, $keyMapping);
            if ($sourceKey !== false) {
                // Use the mapped key
                $hmacData[$key] = $data[$sourceKey] ?? '';
            } else {
                // Use the key directly
                $hmacData[$key] = $data[$key] ?? '';
            }
        }
        
        $concatenatedString = '';
        foreach ($hmacData as $value) {
            $concatenatedString .= $value;
        }

        $calculatedHmac = hash_hmac('sha512', $concatenatedString, $this->hmacSecret);

        return $calculatedHmac;
    }

    /**
     * Calculate final price considering offers
     */
    public function calculateFinalPrice(Membership $membership, ?Offer $offer): float
    {
        $finalPrice = $membership->price;

        if ($offer && $offer->isActive()) {
            if ($offer->discount_type === 'percentage') {
                $finalPrice = $membership->price * (1 - ($offer->discount_value / 100));
            } elseif ($offer->discount_type === 'fixed') {
                $finalPrice = max(0, $membership->price - $offer->discount_value);
            }
        }

        return round($finalPrice, 2);
    }

    /**
     * Create order for class booking
     */
    public function createOrderForBookable(string $authToken, $bookable, Booking $booking): ?array
    {
        try {
            $amountCents = (int)($booking->amount * 100);

            $orderData = [
                'auth_token' => $authToken,
                'delivery_needed' => false,
                'amount_cents' => $amountCents,
                'currency' => 'EGP',
                'items' => [
                    [
                        'name' => $bookable->name,
                        'amount_cents' => $amountCents,
                        'description' => $bookable->name,
                        'quantity' => 1
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/ecommerce/orders', $orderData);

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Paymob class order creation exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Create payment key for class booking
     */
    public function createPaymentKeyForBookable(string $authToken, array $orderData, $bookable, Booking $booking): ?array
    {
        try {
            $amountCents = (int)($booking->amount * 100);
            $user = $booking->user;

            $nameParts = explode(' ', trim($user->name), 2);
            $firstName = $nameParts[0] ?? 'User';
            $lastName = $nameParts[1] ?? 'Name';
            
            $email = $user->email;
            $phone = $user->phone ?? '+201234567890';
            $address = $user->address ?? 'Cairo, Egypt';
            $city = 'Cairo';

            $paymentKeyData = [
                'auth_token' => $authToken,
                'amount_cents' => $amountCents,
                'expiration' => 3600, // 1 hour
                'order_id' => $orderData['id'],
                'billing_data' => [
                    'apartment' => 'NA',
                    'email' => $email,
                    'floor' => 'NA',
                    'first_name' => $firstName,
                    'street' => $address,
                    'building' => 'NA',
                    'phone_number' => $phone,
                    'shipping_method' => 'NA',
                    'postal_code' => 'NA',
                    'city' => $city,
                    'country' => 'EG',
                    'last_name' => $lastName,
                    'state' => 'NA'
                ],
                'currency' => 'EGP',
                'integration_id' => (int)$this->integrationId,
                'lock_order_when_paid' => false,
                'success_url' => route('payments.paymob.callback'),
                'failure_url' => route('payments.paymob.callback'),
                'items' => [
                    [
                        'name' => $bookable->name,
                        'amount_cents' => $amountCents,
                        'description' => 'Class Booking - ' . $bookable->name,
                        'quantity' => 1
                    ]
                ]
            ];

            $response = Http::withHeaders(['Content-Type' => 'application/json',])->timeout(30)->post($this->baseUrl . '/acceptance/payment_keys', $paymentKeyData);

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Paymob class payment key creation exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $orderData['id'] ?? 'unknown'
            ]);
            return null;
        }
    }
}
