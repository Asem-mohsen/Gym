<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BranchScore extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'score' => 'integer',
        'last_review_date' => 'datetime',
        'next_review_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function scoreItems(): HasMany
    {
        return $this->hasMany(BranchScoreItem::class);
    }

    public function scoreHistory(): HasMany
    {
        return $this->hasMany(BranchScoreHistory::class);
    }

    public function reviewRequests(): HasMany
    {
        return $this->hasMany(BranchScoreReviewRequest::class);
    }

    public function getFormattedScoreAttribute(): string
    {
        return number_format($this->score);
    }

    public function getScoreLevelAttribute(): string
    {
        if ($this->score >= 90) return 'excellent';
        if ($this->score >= 80) return 'very_good';
        if ($this->score >= 70) return 'good';
        if ($this->score >= 60) return 'average';
        return 'poor';
    }

    public function getScoreLevelColorAttribute(): string
    {
        return match($this->score_level) {
            'excellent' => 'success',
            'very_good' => 'info',
            'good' => 'primary',
            'average' => 'warning',
            'poor' => 'danger',
        };
    }
}
