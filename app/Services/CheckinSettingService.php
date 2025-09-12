<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Models\{SiteSetting, GymCheckinSetting};
use App\Repositories\CheckinSettingRepository;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\DB;

class CheckinSettingService
{
    public function __construct(
        private CheckinSettingRepository $checkinSettingRepository,
        private QrCodeService $qrCodeService
    ) {}

    /**
     * Get gym check-in settings
     */
    public function getGymCheckinSettings(int $gymId): ?GymCheckinSetting
    {
        return $this->checkinSettingRepository->getByGymId($gymId);
    }

    /**
     * Create check-in settings
     */
    public function createCheckinSettings(array $data): GymCheckinSetting
    {
        return DB::transaction(function () use ($data) {
            $data = $this->processBranchData($data);
            return $this->checkinSettingRepository->create($data);
        });
    }

    /**
     * Update check-in settings
     */
    public function updateCheckinSettings(GymCheckinSetting $checkinSetting, array $data): GymCheckinSetting
    {
        return DB::transaction(function () use ($checkinSetting, $data) {
            $data = $this->processBranchData($data);
            return $this->checkinSettingRepository->update($checkinSetting, $data);
        });
    }

    /**
     * Toggle check-in method
     */
    public function toggleCheckinMethod(int $gymId, string $method, bool $enabled): void
    {
        $checkinSetting = $this->getGymCheckinSettings($gymId);
        
        if (!$checkinSetting) {
            // Create default settings if none exist
            $checkinSetting = $this->createCheckinSettings([
                'site_setting_id' => $gymId,
                'preferred_checkin_method' => 'both',
                'enable_self_scan' => true,
                'enable_gate_scan' => true,
                'allow_multiple_checkins_per_day' => false,
                'checkin_cooldown_minutes' => 5,
            ]);
        }

        $updateData = [];
        
        if ($method === 'self_scan') {
            $updateData['enable_self_scan'] = $enabled;
        } elseif ($method === 'gate_scan') {
            $updateData['enable_gate_scan'] = $enabled;
        }

        // Update preferred method if needed
        if (!$enabled) {
            if ($method === 'self_scan' && $checkinSetting->enable_gate_scan) {
                $updateData['preferred_checkin_method'] = 'gate_scan';
            } elseif ($method === 'gate_scan' && $checkinSetting->enable_self_scan) {
                $updateData['preferred_checkin_method'] = 'self_scan';
            } else {
                $updateData['preferred_checkin_method'] = 'both';
            }
        } else {
            if ($checkinSetting->enable_self_scan && $checkinSetting->enable_gate_scan) {
                $updateData['preferred_checkin_method'] = 'both';
            } else {
                $updateData['preferred_checkin_method'] = $method;
            }
        }

        $this->checkinSettingRepository->update($checkinSetting, $updateData);
    }

    /**
     * Get check-in statistics
     */
    public function getCheckinStats(int $gymId, string $period = 'today'): array
    {
        $checkinService = app(CheckinService::class);
        return $checkinService->getGymCheckinStats($gymId, $period);
    }

    /**
     * Get recent check-ins for a gym
     */
    public function getRecentCheckins(int $gymId, int $limit = 6): Collection
    {
        $checkinService = app(CheckinService::class);
        return $checkinService->getRecentCheckins($gymId, $limit);
    }

    /**
     * Get default settings
     */
    public function getDefaultSettings(): array
    {
        return [
            'preferred_checkin_method' => 'both',
            'enable_self_scan' => true,
            'enable_gate_scan' => true,
            'require_branch_selection' => false,
            'allow_multiple_checkins_per_day' => false,
            'checkin_cooldown_minutes' => 5,
            'enabled_branches' => [],
        ];
    }

    /**
     * Process branch data for check-in settings
     */
    private function processBranchData(array $data): array
    {
        // If enabled_branches is empty or null, it means all branches are enabled
        if (empty($data['enabled_branches'])) {
            $data['enabled_branches'] = [];
        }
        
        // Ensure enabled_branches is always an array
        if (!is_array($data['enabled_branches'])) {
            $data['enabled_branches'] = [];
        }
        
        return $data;
    }

    /**
     * Generate test QR code for gym
     */
    public function generateTestQrCode(SiteSetting $gym): array
    {
        $qrUrl = $this->qrCodeService->generateGymQrUrl($gym);
        $qrData = $this->qrCodeService->generateQrData($qrUrl);

        return [
            'qr_url' => $qrUrl,
            'qr_data' => $qrData,
            'gym_name' => $gym->gym_name,
        ];
    }
}
