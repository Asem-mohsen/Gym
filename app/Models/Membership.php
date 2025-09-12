<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Translatable\HasTranslations;
use App\Enums\MembershipPeriod;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'memberships';

    protected $guarded = ['id'];

    public $translatable = ['name','subtitle', 'general_description'];

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function offers(): MorphToMany
    {
        return $this->morphToMany(Offer::class, 'offerable');
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'membership_features');
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function payment(): MorphOne
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function getPeriodEnum(): ?MembershipPeriod
    {
        return MembershipPeriod::fromString($this->period);
    }

    /**
     * Calculate end date for this membership
     */
    public function calculateEndDate(?Carbon $startDate = null): Carbon
    {
        $period = $this->getPeriodEnum();
        
        if (!$period) {
            return ($startDate ?? now())->addMonth();
        }
        
        return $period->calculateEndDate($startDate);
    }

    public function hasValidPeriod(): bool
    {
        return MembershipPeriod::isValid($this->period);
    }

    /**
     * Get the billing interval for this membership
     */
    public function getBillingInterval(): string
    {
        return $this->billing_interval ?? MembershipPeriod::getBillingIntervalFromPeriod($this->period);
    }

    /**
     * Check if this is a daily membership
     */
    public function isDailyMembership(): bool
    {
        return $this->period === MembershipPeriod::DAY->value;
    }

    /**
     * Check if this is a monthly membership
     */
    public function isMonthlyMembership(): bool
    {
        return in_array($this->period, [
            MembershipPeriod::MONTH->value,
            MembershipPeriod::THREE_MONTHS->value,
            MembershipPeriod::SIX_MONTHS->value
        ]);
    }

    /**
     * Check if this is a yearly membership
     */
    public function isYearlyMembership(): bool
    {
        return in_array($this->period, [
            MembershipPeriod::YEAR->value,
            MembershipPeriod::TWO_YEARS->value,
            MembershipPeriod::THREE_YEARS->value,
            MembershipPeriod::FOUR_YEARS->value,
            MembershipPeriod::SIX_YEARS->value
        ]);
    }
}
