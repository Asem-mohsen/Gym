<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $guarded = ['id'];

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'branch_machine');
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class, 'machine_id');
    }

}
