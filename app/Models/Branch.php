<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Branch extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia , HasTranslations;

    protected $guarded = ['id'];

    public $translatable = ['name','location'];

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

    public function galleries(): MorphMany
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_branch')->withPivot('is_available')->withTimestamps();
    }
}
