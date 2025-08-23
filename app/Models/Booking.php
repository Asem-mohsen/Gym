<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = [];
    
    protected $casts = [
        'booking_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }

    public function pricing(): BelongsTo
    {
        return $this->belongsTo(ClassPricing::class, 'pricing_id');
    }
}
