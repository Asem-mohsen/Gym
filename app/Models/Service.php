<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Service extends Model implements HasMedia
{
    use HasFactory, HasTranslations, SoftDeletes , InteractsWithMedia;

    protected $table = 'services';

    protected $fillable = ['name', 'description', 'price' , 'duration'];

    public $translatable = ['name','description'];

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
}
