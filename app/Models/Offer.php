<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Offer extends Model
{
    use HasFactory , HasTranslations , SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['remaining_days'];
    public $translatable = ['title','description'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($offer) {
            $offer->status = now()->toDateString() === $offer->start_date ? 1 : 0;
        });
    }
    
    public function offerables(): HasMany
    {
        return $this->hasMany(Offerable::class);
    }

    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'offerable');
    }

    public function memberships(): MorphToMany
    {
        return $this->morphedByMany(Membership::class, 'offerable');
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    public function getRemainingDaysAttribute()
    {
        return $this->end_date ? Carbon::today()->diffInDays(Carbon::parse($this->end_date), false) : null;
    }
    
    public function getUsersCountAttribute()
    {
        return Payment::where('offer_id', $this->id)->distinct('user_id')->count('user_id');
    }

}
