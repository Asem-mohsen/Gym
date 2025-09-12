<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
        } catch (Exception $e) {
            // Log error but don't fail
            Log::warning('Failed to get location from IP: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Sort gyms by score and location proximity
     */
    public function sortGymsByScoreAndLocation(Collection $gyms, ?array $userLocation = null): Collection
    {
        return $gyms->sortBy(function ($gym) use ($userLocation) {
            $averageScore = $gym->branches->avg('score_value') ?? 0;
            $distanceScore = 0;
            
            // If we have user location with coordinates, calculate distance
            if ($userLocation && isset($userLocation['latitude']) && isset($userLocation['longitude'])) {
                $minDistance = $this->getMinimumDistanceToGym($gym, $userLocation);
                
                if ($minDistance !== null) {
                    $distanceScore = max(0, 100 - ($minDistance * 2)); // 50km = 0 score, 0km = 100 score
                }
            }
            
            if ($userLocation && isset($userLocation['region']) && $distanceScore === 0) {
                $userRegion = strtolower($userLocation['region']);
                
                $hasLocalBranch = $gym->branches->contains(function ($branch) use ($userRegion) {
                    $branchLocation = $branch->getTranslation('location', app()->getLocale());
                    $branchCity = $branch->city;
                    $branchRegion = $branch->region;
                    
                    return stripos($branchLocation, $userRegion) !== false ||
                           ($branchCity && stripos($branchCity, $userRegion) !== false) ||
                           ($branchRegion && stripos($branchRegion, $userRegion) !== false);
                });
                
                if ($hasLocalBranch) {
                    $distanceScore = 50;
                }
            }
            
            return -($averageScore + $distanceScore);
        });
    }
    
    /**
     * Calculate minimum distance from user location to any branch of the gym
     */
    private function getMinimumDistanceToGym($gym, array $userLocation): ?float
    {
        $userLat = $userLocation['latitude'];
        $userLng = $userLocation['longitude'];
        $minDistance = null;
        
        foreach ($gym->branches as $branch) {
            if ($branch->latitude && $branch->longitude) {
                $distance = $this->calculateDistance($userLat, $userLng, $branch->latitude, $branch->longitude);
                
                if ($minDistance === null || $distance < $minDistance) {
                    $minDistance = $distance;
                }
            }
        }
        
        return $minDistance;
    }
    
    /**
     * Calculate distance between two points using Haversine formula
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }

    /**
     * Get distance information for a gym from user location
     */
    public function getGymDistanceInfo($gym, ?array $userLocation = null): ?array
    {
        if (!$userLocation || !isset($userLocation['latitude']) || !isset($userLocation['longitude'])) {
            return null;
        }
        
        $minDistance = $this->getMinimumDistanceToGym($gym, $userLocation);
        
        if ($minDistance === null) {
            return null;
        }
        
        return [
            'distance' => $minDistance,
            'formatted_distance' => $this->formatDistance($minDistance),
            'is_nearby' => $minDistance <= 10, // Within 10km
        ];
    }
    
    /**
     * Format distance for display
     */
    private function formatDistance(float $distance): string
    {
        if ($distance < 1) {
            return round($distance * 1000) . 'm';
        }
        
        return round($distance, 1) . 'km';
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
