<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            $invitation->qr_code = Str::random(32);
            $invitation->expires_at = now()->addDays(30);
        });
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_used', false)->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function canBeUsed(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    public function markAsUsed(User $user): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'used_by_id' => $user->id,
        ]);
    }

    public function getQrCodeUrlAttribute(): string
    {
        return route('user.invitations.scan', ['siteSetting' => $this->gym->slug, 'qrCode' => $this->qr_code]);
    }
}
