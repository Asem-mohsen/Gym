<?php

namespace App\Services;

use Illuminate\Support\Str;

class PasswordGenerationService
{
    /**
     * Generate a secure random password
     */
    public function generateSecurePassword(int $length = 12): string
    {
        // Define character sets
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';
        
        // Ensure at least one character from each set
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];
        
        // Fill the rest with random characters from all sets
        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
        
        // Shuffle the password to make it more random
        return str_shuffle($password);
    }

    /**
     * Generate a temporary password for onboarding
     */
    public function generateTemporaryPassword(): string
    {
        return $this->generateSecurePassword(10);
    }
}
