<?php

namespace App\Services;

use App\Models\{Checkin, User, SiteSetting, Subscription};
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log};
use Carbon\Carbon;

class CheckinService
{
    /**
     * Log a check-in for a user
     */
    public function __construct(
        private SubscriptionRepository $subscriptionRepository
    ) {}

    public function logVisit(int $userId, int $gymId, string $checkinType, ?Request $request = null, array $additionalMetadata = []): Checkin
    {
        return DB::transaction(function () use ($userId, $gymId, $checkinType, $request, $additionalMetadata) {
            // Build metadata
            $metadata = $this->buildMetadata($request);
            $metadata = array_merge($metadata, $additionalMetadata);
            
            // Create the check-in record
            $checkin = Checkin::create([
                'user_id' => $userId,
                'site_setting_id' => $gymId,
                'branch_id' => $additionalMetadata['branch_id'] ?? null,
                'checkin_type' => $checkinType,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'metadata' => $metadata,
            ]);

            // Update user's last visit timestamp
            User::where('id', $userId)->update([
                'last_visit_at' => now(),
            ]);

            Log::info('User check-in logged', [
                'user_id' => $userId,
                'gym_id' => $gymId,
                'checkin_type' => $checkinType,
                'checkin_id' => $checkin->id,
            ]);

            return $checkin;
        });
    }

    /**
     * Validate if a user can check in to a gym
     */
    public function validateCheckin(User $user, $gym, ?string $checkinType = null): array
    {
        if (!$user->gyms()->where('site_setting_id', $gym->id)->exists()) {
            return [
                'valid' => false,
                'message' => 'User is not associated with this gym.',
                'code' => 'USER_NOT_ASSOCIATED'
            ];
        }

        // Check if user has an active subscription
        $activeSubscription = $this->subscriptionRepository->getActiveSubscription($user->id, $gym->id);

        if (!$activeSubscription) {
            return [
                'valid' => false,
                'message' => 'User does not have an active membership.',
                'code' => 'NO_ACTIVE_MEMBERSHIP'
            ];
        }

        // Get gym check-in settings
        $checkinSettings = $gym->checkinSettings()->first();
        
        // Validate check-in method if specified
        if ($checkinType && $checkinSettings) {
            if ($checkinType === 'self_scan' && !$checkinSettings->isSelfScanEnabled()) {
                return [
                    'valid' => false,
                    'message' => 'Self-scan check-in is not enabled for this gym.',
                    'code' => 'METHOD_NOT_ENABLED'
                ];
            }
            
            if ($checkinType === 'gate_scan' && !$checkinSettings->isGateScanEnabled()) {
                return [
                    'valid' => false,
                    'message' => 'Gate-scan check-in is not enabled for this gym.',
                    'code' => 'METHOD_NOT_ENABLED'
                ];
            }
        }

        $todayCheckins = Checkin::where('user_id', $user->id)
            ->where('site_setting_id', $gym->id)
            ->whereDate('created_at', today())
            ->count();

        $allowMultiple = $checkinSettings ? $checkinSettings->allow_multiple_checkins_per_day : false;
        
        if ($todayCheckins > 0 && !$allowMultiple) {
            return [
                'valid' => true,
                'message' => 'You can only check in once per day.',
                'code' => 'ALREADY_CHECKED_IN',
                'warning' => true
            ];
        }

        return [
            'valid' => true,
            'message' => 'Check-in validated successfully.',
            'code' => 'VALID'
        ];
    }

    /**
     * Get check-in statistics for a gym
     */
    public function getGymCheckinStats(int $gymId, string $period = 'today'): array
    {
        $query = Checkin::where('site_setting_id', $gymId);

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $totalCheckins = $query->count();
        $uniqueUsers = $query->distinct('user_id')->count('user_id');
        
        $selfScans = (clone $query)->where('checkin_type', 'self_scan')->count();
        $gateScans = (clone $query)->where('checkin_type', 'gate_scan')->count();

        return [
            'total_checkins' => $totalCheckins,
            'unique_users' => $uniqueUsers,
            'self_scans' => $selfScans,
            'gate_scans' => $gateScans,
            'period' => $period,
        ];
    }

    /**
     * Get user's check-in history
     */
    public function getUserCheckinHistory(int $userId, int $gymId, int $limit = 10): array
    {
        $checkins = Checkin::where('user_id', $userId)
            ->where('site_setting_id', $gymId)
            ->with(['branch'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return [
            'checkins' => $checkins,
            'total_checkins' => Checkin::where('user_id', $userId)
                ->where('site_setting_id', $gymId)
                ->count(),
            'last_checkin' => $checkins->first(),
        ];
    }

    /**
     * Build metadata for check-in
     */
    private function buildMetadata(?Request $request): array
    {
        if (!$request) {
            return [];
        }

        return [
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'accept_language' => $request->header('accept-language'),
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Check if user has checked in recently (within specified minutes)
     */
    public function hasRecentCheckin(int $userId, int $gymId, ?int $minutes = null): bool
    {
        // Get cooldown setting from gym if not specified
        if ($minutes === null) {
            $gym = SiteSetting::findOrFail($gymId);
            $checkinSettings = $gym->checkinSettings()->first();
            $minutes = $checkinSettings ? $checkinSettings->checkin_cooldown_minutes : 5;
        }

        return Checkin::where('user_id', $userId)
            ->where('site_setting_id', $gymId)
            ->where('created_at', '>=', now()->subMinutes($minutes))
            ->exists();
    }

    /**
     * Get recent check-ins for a gym
     */
    public function getRecentCheckins(int $gymId, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Checkin::where('site_setting_id', $gymId)
            ->with(['user', 'branch'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
