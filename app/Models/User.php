<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable , HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'is_admin',
        'role_id',
        'password',
        'gender',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class , 'role_id' , 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
