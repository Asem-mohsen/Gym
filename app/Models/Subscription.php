<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function getSiteSettingIdAttribute(): int
    {
        return $this->branch->site_setting_id;
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class, 'inviter_id', 'user_id');
    }

    public function getRemainingInvitationsAttribute(): int
    {
        return max(0, $this->membership->invitation_limit - $this->invitations_used);
    }

    public function canSendInvitation(): bool
    {
        return $this->getRemainingInvitationsAttribute() > 0;
    }

    /**
     * Check if this is a one-day membership subscription
     */
    public function isOneDayMembership(): bool
    {
        return $this->membership->period === \App\Enums\MembershipPeriod::DAY->value;
    }

    /**
     * Check if the subscription is expired (including one-day memberships)
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               $this->end_date < now()->toDateString();
    }

    /**
     * Check if the subscription is active (including one-day memberships)
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               $this->end_date >= now()->toDateString();
    }

    /**
     * Get the remaining days for this subscription
     */
    public function getRemainingDaysAttribute(): int
    {
        $endDate = \Carbon\Carbon::parse($this->end_date);
        $today = \Carbon\Carbon::today();
        
        if ($endDate->isPast()) {
            return 0;
        }
        
        return $today->diffInDays($endDate);
    }

    /**
     * Calculate end date based on membership period and start date
     */
    public function calculateEndDateFromStartDate(): \Carbon\Carbon
    {
        $startDate = \Carbon\Carbon::parse($this->start_date);
        return $this->membership->calculateEndDate($startDate);
    }
}
