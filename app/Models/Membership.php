<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class Membership extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'memberships';

    protected $fillable = ['name', 'description' , 'price' , 'status'];

    public $translatable = ['name','description'];

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}
