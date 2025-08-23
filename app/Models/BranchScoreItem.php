<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchScoreItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'points' => 'integer',
        'is_achieved' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function branchScore(): BelongsTo
    {
        return $this->belongsTo(BranchScore::class);
    }

    public function scoreCriteria(): BelongsTo
    {
        return $this->belongsTo(ScoreCriteria::class);
    }

    public function getPointsDisplayAttribute(): string
    {
        return $this->points > 0 ? "+{$this->points}" : "{$this->points}";
    }

    public function getStatusColorAttribute(): string
    {
        return $this->is_achieved ? 'success' : 'danger';
    }
}
