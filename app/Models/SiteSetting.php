<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class SiteSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia , HasTranslations;

    protected $guarded = ['id'];

    public $translatable = ['gym_name','address', 'description'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gym_logo')->singleFile();
        $this->addMediaCollection('favicon')->singleFile();
        $this->addMediaCollection('email_logo')->singleFile();
        $this->addMediaCollection('footer_logo')->singleFile();
    }
    
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class , 'owner_id');
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'site_setting_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'gym_user', 'site_setting_id', 'user_id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class, 'site_setting_id');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class, 'site_setting_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'site_setting_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'site_setting_id');
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class, 'site_setting_id');
    }

}
