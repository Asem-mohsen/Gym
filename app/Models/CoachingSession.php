<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachingSession extends Model
{
    use HasFactory;

    protected $table = 'coaching_sessions';

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

}
