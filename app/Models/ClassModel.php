<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\LaravelPackageTools\Concerns\Package\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ClassModel extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia , HasTranslations;

    protected $table = 'classes';
    protected $guarded = ['id'];
    protected $casts = [
        'description' => 'array',
    ];

    public function trainers()
    {
        return $this->belongsToMany(User::class, 'class_trainer', 'class_id', 'trainer_id');
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'class_id');
    }

    public function pricings()
    {
        return $this->hasMany(ClassPricing::class, 'class_id');
    }

    public function siteSetting()
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
} 