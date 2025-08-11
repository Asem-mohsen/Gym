<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainerInformation extends Model
{
    use HasFactory;

    protected $table = 'trainer_information';

    protected $fillable = [
        'user_id',
        'weight',
        'height',
        'date_of_birth',
        'brief_description',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
