<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'status',
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

    public function gyms(): BelongsToMany
    {
        return $this->belongsToMany(SiteSetting::class, 'gym_user', 'user_id', 'site_setting_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function site(): HasOne
    {
        return $this->hasOne(SiteSetting::class , 'owner_id');
    }

    public function managedBranches(): HasMany
    {
        return $this->hasMany(Branch::class, 'manager_id');
    }

    public function getSiteSettingIdAttribute()
    {
        return $this->site?->id;
    }

}
