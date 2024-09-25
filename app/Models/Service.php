<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = ['name', 'description' , 'description_ar' , 'price' , 'duration'];

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}
