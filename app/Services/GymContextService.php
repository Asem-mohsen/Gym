<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class GymContextService
{
    /**
     * Store gym context for the current request
     */
    public function storeGymContext(int $gymId, string $gymSlug, string $gymName, string $gymLogo): void
    {
        // Store in session for web users
        Session::put('current_gym_id', $gymId);
        Session::put('current_gym_slug', $gymSlug);
        Session::put('current_gym_name', $gymName);
        Session::put('current_gym_logo', $gymLogo);
        
        $guestKey = "guest_gym_context_" . request()->ip();
        Cache::put($guestKey, [
            'id' => $gymId,
            'slug' => $gymSlug,
            'name' => $gymName,
            'logo' => $gymLogo
        ], now()->addHours(1));
    }

    /**
     * Get current gym context from session or cache
     */
    public function getCurrentGymContext(): ?array
    {
        // First try to get from session
        if (Session::has('current_gym_id')) {
            return [
                'id' => Session::get('current_gym_id'),
                'slug' => Session::get('current_gym_slug'),
                'name' => Session::get('current_gym_name'),
                'logo' => Session::get('current_gym_logo')
            ];
        }

        // If not in session, try to get from cache based on IP
        $guestKey = "guest_gym_context_" . request()->ip();
        $cachedContext = Cache::get($guestKey);
        
        if ($cachedContext) {
            // Store in session for future use
            $this->storeGymContext(
                $cachedContext['id'],
                $cachedContext['slug'],
                $cachedContext['name'],
                $cachedContext['logo']
            );
            
            return $cachedContext;
        }

        return null;
    }

    /**
     * Clear current gym context
     */
    public function clearGymContext(): void
    {
        Session::forget(['current_gym_id', 'current_gym_slug', 'current_gym_name', 'current_gym_logo']);
        
        $guestKey = "guest_gym_context_" . request()->ip();
        Cache::forget($guestKey);
    }

    /**
     * Update gym context (clear old and set new)
     */
    public function updateGymContext(int $gymId, string $gymSlug, string $gymName, string $gymLogo): void
    {
        $this->clearGymContext();
        $this->storeGymContext($gymId, $gymSlug, $gymName, $gymLogo);
    }

    /**
     * Store gym context for API users
     */
    public function storeGymContextForApi(int $userId, int $gymId): void
    {
        Cache::put("user_{$userId}_current_gym", $gymId, now()->addHours(24));
    }

    /**
     * Validate if user is in correct gym context
     */
    public function validateGymContext(int $expectedGymId): bool
    {
        $currentContext = $this->getCurrentGymContext();
        return $currentContext && $currentContext['id'] == $expectedGymId;
    }
}
