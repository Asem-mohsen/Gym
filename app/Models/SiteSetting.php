<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class SiteSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia , HasTranslations;

    protected $guarded = ['id'];

    public $translatable = ['gym_name','address', 'description'];

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'site_setting_id');
    }
}
