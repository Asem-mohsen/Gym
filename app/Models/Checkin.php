<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Check if this is a self-scan check-in
     */
    public function isSelfScan(): bool
    {
        return $this->checkin_type === 'self_scan';
    }

    /**
     * Check if this is a gate-scan check-in
     */
    public function isGateScan(): bool
    {
        return $this->checkin_type === 'gate_scan';
    }

    /**
     * Get the check-in type label
     */
    public function getCheckinTypeLabelAttribute(): string
    {
        return match($this->checkin_type) {
            'self_scan' => 'Self Scan',
            'gate_scan' => 'Gate Scan',
            default => 'Unknown'
        };
    }
}