<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Branch extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia , HasTranslations, SoftDeletes;

    protected $guarded = ['id'];

    public $translatable = ['name','location'];
    
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function siteSetting(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class, 'branch_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function machines(): BelongsToMany
    {
        return $this->belongsToMany(Machine::class, 'branch_machine');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'branch_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'branch_id');
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class, 'branch_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'branch_id');
    }

    public function galleries(): MorphMany
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_branch')->withPivot('is_available')->withTimestamps();
    }

    public function score(): HasOne
    {
        return $this->hasOne(BranchScore::class);
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_branch', 'branch_id', 'user_id');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'class_branch', 'branch_id', 'class_id');
    }

    public function getScoreValueAttribute(): int
    {
        return $this->score()->first()?->score ?? 0;
    }

    public function getScoreLevelAttribute(): string
    {
        $score = $this->score_value;
        if ($score >= 90) return 'excellent';
        if ($score >= 80) return 'very_good';
        if ($score >= 70) return 'good';
        if ($score >= 60) return 'average';
        return 'poor';
    }

}
