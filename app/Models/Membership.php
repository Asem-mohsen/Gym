<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Translatable\HasTranslations;

class Membership extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'memberships';

    protected $guarded = ['id'];

    public $translatable = ['name','description'];

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function offers(): MorphToMany
    {
        return $this->morphToMany(Offer::class, 'offerable');
    }
}
