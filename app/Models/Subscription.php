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
}
