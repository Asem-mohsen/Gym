<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

/**
 * GymContextService - Manages gym context across the application
 * 
 * This service handles setting and retrieving gym context for both web and API users.
 * It's particularly useful for email links where gym context needs to be established
 * from URL parameters.
 * 
 * @example
 * // Setting gym context from email links
 * $gymContextService = app(GymContextService::class);
 * 
 * // Method 1: From URL path (recommended for controllers)
 * $success = $gymContextService->setGymContextFromUrl($request->path());
 * 
 * // Method 2: From slug directly
 * $success = $gymContextService->setGymContextFromSlug('test-gym');
 * 
 * // Getting current context
 * $context = $gymContextService->getCurrentGymContext();
 * 
 * // Checking if context is set
 * if ($context) {
 *     $gymId = $context['id'];
 *     $gymSlug = $context['slug'];
 *     $gymName = $context['name'];
 * }
 */
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
     * Get gym context for API users
     */
    public function getGymContextForApi(int $userId): ?int
    {
        return Cache::get("user_{$userId}_current_gym");
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

    /**
     * Get gym context for forms
     */
    public function getGymContextForForm(): ?array
    {
        $context = $this->getCurrentGymContext();
        if ($context) {
            return [
                'id' => $context['id'],
                'slug' => $context['slug'],
                'name' => $context['name'],
                'logo' => $context['logo']
            ];
        }
        return null;
    }
    
    /**
     * Set gym context from slug (useful for email links)
     * 
     * @param string $gymSlug The gym slug to set context for
     * @return bool True if gym was found and context was set, false otherwise
     * 
     * @example
     * // In any controller or service
     * $gymContextService = app(GymContextService::class);
     * $success = $gymContextService->setGymContextFromSlug('test-gym');
     * if ($success) {
     *     // Gym context is now set in session
     * }
     */
    public function setGymContextFromSlug(string $gymSlug): bool
    {
        $siteSetting = SiteSetting::where('slug', $gymSlug)->first();
        
        if ($siteSetting) {
            $this->storeGymContext(
                $siteSetting->id,
                $siteSetting->slug,
                $siteSetting->gym_name,
                $siteSetting->getFirstMediaUrl('gym_logo') ?? ''
            );
            return true;
        }
        
        return false;
    }
    
    /**
     * Extract gym slug from URL path and set context
     * 
     * @param string $urlPath The URL path to extract gym slug from
     * @return bool True if gym slug was found and context was set, false otherwise
     * 
     * @example
     * // In any controller
     * $gymContextService = app(GymContextService::class);
     * $success = $gymContextService->setGymContextFromUrl($request->path());
     * // This will extract 'test' from '/gym/test/auth/admin-setup-password'
     */
    public function setGymContextFromUrl(string $urlPath): bool
    {
        $pathSegments = explode('/', trim($urlPath, '/'));
        $gymSlug = null;
        
        // Look for the gym slug in the path segments (e.g., /gym/test/auth/...)
        foreach ($pathSegments as $index => $segment) {
            if ($segment === 'gym' && isset($pathSegments[$index + 1])) {
                $gymSlug = $pathSegments[$index + 1];
                break;
            }
        }
        
        if ($gymSlug) {
            return $this->setGymContextFromSlug($gymSlug);
        }
        
        return false;
    }
}
