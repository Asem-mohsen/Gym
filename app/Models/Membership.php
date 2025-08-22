<?php

namespace App\Models;

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

class Membership extends Model
{
    use HasFactory, HasTranslations;

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
    public function calculateEndDate(?\Carbon\Carbon $startDate = null): \Carbon\Carbon
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
}
