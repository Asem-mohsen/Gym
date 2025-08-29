<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymCheckinSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'enable_self_scan' => 'boolean',
        'enable_gate_scan' => 'boolean',
        'require_branch_selection' => 'boolean',
        'allow_multiple_checkins_per_day' => 'boolean',
        'enabled_branches' => 'array',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    /**
     * Check if self-scan check-in is enabled
     */
    public function isSelfScanEnabled(): bool
    {
        return $this->enable_self_scan;
    }

    /**
     * Check if gate-scan check-in is enabled
     */
    public function isGateScanEnabled(): bool
    {
        return $this->enable_gate_scan;
    }

    /**
     * Check if both methods are enabled
     */
    public function isBothEnabled(): bool
    {
        return $this->enable_self_scan && $this->enable_gate_scan;
    }

    /**
     * Get the preferred check-in method
     */
    public function getPreferredMethod(): string
    {
        return $this->preferred_checkin_method;
    }

    /**
     * Check if a specific branch is enabled for check-in
     */
    public function isBranchEnabled(int $branchId): bool
    {
        if (empty($this->enabled_branches)) {
            return true; // If no branches specified, all are enabled
        }

        return in_array($branchId, $this->enabled_branches);
    }

    /**
     * Get enabled branches for this gym
     */
    public function getEnabledBranches()
    {
        if (empty($this->enabled_branches)) {
            return $this->gym->branches;
        }

        return $this->gym->branches()->whereIn('id', $this->enabled_branches)->get();
    }

    /**
     * Get hardware requirements for gate-scan method
     */
    public function getHardwareRequirements(): array
    {
        if (!$this->enable_gate_scan) {
            return [];
        }

        return [
            'qr_scanner' => 'QR Code Scanner Hardware (recommended for high-traffic gyms)',
            'mobile_app' => 'Staff Mobile App with QR Scanner (alternative option)',
            'tablet' => 'Tablet with Camera for QR Scanning',
        ];
    }
}
