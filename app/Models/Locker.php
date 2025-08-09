<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'is_locked' => 'boolean',
    ];
}
