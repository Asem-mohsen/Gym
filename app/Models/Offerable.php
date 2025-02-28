<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Offerable extends Model
{
    use HasFactory;

    protected $fillable = ['offer_id', 'offerable_id', 'offerable_type'];

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function offerable(): MorphTo
    {
        return $this->morphTo();
    }
}
