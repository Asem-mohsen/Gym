<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LocationService
{
    /**
     * Get user's location from request or IP
     */
    public function getUserLocation(Request $request): ?array
    {
        if ($request->has('latitude') && $request->has('longitude')) {
            return [
                'latitude' => (float) $request->latitude,
                'longitude' => (float) $request->longitude,
                'city' => $request->get('city'),
                'region' => $request->get('region')
            ];
        }

        $ip = $request->ip();
        if ($ip && $ip !== '127.0.0.1') {
            return $this->getLocationFromIP($ip);
        }

        return null;
    }

    /**
     * Get location from IP address (basic implementation)
     */
    private function getLocationFromIP(string $ip): ?array
    {
        try {
            // Using a free IP geolocation service
            $response = file_get_contents("http://ip-api.com/json/{$ip}");
            $data = json_decode($response, true);

            if ($data && $data['status'] === 'success') {
                return [
                    'latitude' => (float) $data['lat'],
                    'longitude' => (float) $data['lon'],
                    'city' => $data['city'] ?? null,
                    'region' => $data['regionName'] ?? null,
                    'country' => $data['country'] ?? null
                ];
            }
        } catch (\Exception $e) {
            // Log error but don't fail
            \Illuminate\Support\Facades\Log::warning('Failed to get location from IP: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Sort gyms by score and location proximity
     */
    public function sortGymsByScoreAndLocation(Collection $gyms, ?array $userLocation = null): Collection
    {
        return $gyms->sortByDesc(function ($gym) use ($userLocation) {

            $averageScore = $gym->branches->avg('score_value') ?? 0;
            
            // If we have user location, prioritize gyms in the same region
            if ($userLocation && isset($userLocation['region'])) {
                $userRegion = strtolower($userLocation['region']);
                
                // Check if any branch is in the same region
                $hasLocalBranch = $gym->branches->contains(function ($branch) use ($userRegion) {
                    $branchLocation = $branch->getTranslation('location', app()->getLocale());
                    return stripos($branchLocation, $userRegion) !== false;
                });
                
                // If gym has local branches, give it a higher priority
                if ($hasLocalBranch) {
                    return $averageScore + 1000; // Add 1000 to prioritize local gyms
                }
            }
            
            return $averageScore;
        });
    }

    /**
     * Extract city/region from location string
     */
    public function extractRegionFromLocation(string $location): ?string
    {
        // Common Egyptian cities/regions
        $egyptianRegions = [
            'cairo', 'alexandria', 'giza', 'sharm el sheikh', 'hurghada', 
            'luxor', 'aswan', 'port said', 'suez', 'ismailia', 'mansoura',
            'tanta', 'assiut', 'beni suef', 'fayoum', 'minya', 'sohag',
            'qena', 'bani suwayf', 'al minya', 'aswan', 'qina', 'red sea',
            'new valley', 'matruh', 'north sinai', 'south sinai'
        ];

        $locationLower = strtolower($location);
        
        foreach ($egyptianRegions as $region) {
            if (stripos($locationLower, $region) !== false) {
                return ucfirst($region);
            }
        }

        return null;
    }
}
