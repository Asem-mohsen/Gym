<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class QrCodeService
{
    /**
     * Generate a personal QR code token for a user
     */
    public function generatePersonalQrToken(User $user, SiteSetting $gym): string
    {
        $data = [
            'user_id' => $user->id,
            'gym_id' => $gym->id,
            'timestamp' => now()->timestamp,
            'nonce' => Str::random(16),
        ];

        return Crypt::encryptString(json_encode($data));
    }

    /**
     * Decrypt and validate a personal QR token
     */
    public function decryptPersonalQrToken(string $token): ?array
    {
        try {
            $decrypted = Crypt::decryptString($token);
            $data = json_decode($decrypted, true);

            if (!$data || !isset($data['user_id'], $data['gym_id'], $data['timestamp'])) {
                return null;
            }

            // Check if token is not too old (24 hours)
            if (now()->timestamp - $data['timestamp'] > 86400) {
                return null;
            }

            return $data;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Generate a static QR code URL for gym entrance
     */
    public function generateGymQrUrl(SiteSetting $gym): string
    {
        $identifier = $gym->slug ?? $gym->id;

        return url("/gym/{$identifier}/checkin/self");
    }

    /**
     * Generate a personal QR code URL for a user
     */
    public function generatePersonalQrUrl(User $user, SiteSetting $gym): string
    {
        $token = $this->generatePersonalQrToken($user, $gym);
        return $token;
    }

    /**
     * Generate QR code data for display (can be used with QR libraries)
     */
    public function generateQrData(string $url): array
    {
        return [
            'url' => $url,
            'size' => 300,
            'format' => 'png',
            'error_correction' => 'M', // Medium error correction
        ];
    }


    /**
     * Generate a personal QR code URL for a user (web endpoint)
     */
    public function generatePersonalQrUrlWithEndpoint(User $user, SiteSetting $gym): string
    {
        $token = $this->generatePersonalQrToken($user, $gym);
        $identifier = $gym->slug ?? $gym->id;
        return url("/gym/{$identifier}/checkin/gate?token={$token}");
    }

    /**
     * Generate a personal QR code URL for API validation
     */
    public function generatePersonalQrUrlForApi(User $user, SiteSetting $gym): string
    {
        $token = $this->generatePersonalQrToken($user, $gym);
        $identifier = $gym->slug ?? $gym->id;
        return url("/api/v1/{$identifier}/checkin/validate-token?token={$token}");
    }


    /**
     * Decrypt QR token (alias for decryptPersonalQrToken for consistency)
     */
    public function decryptQrToken(string $token): ?array
    {
        return $this->decryptPersonalQrToken($token);
    }
}
