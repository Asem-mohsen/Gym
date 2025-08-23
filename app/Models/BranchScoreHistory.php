<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchScoreHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'old_score' => 'integer',
        'new_score' => 'integer',
        'change_amount' => 'integer',
        'changed_at' => 'datetime',
    ];

    public function branchScore(): BelongsTo
    {
        return $this->belongsTo(BranchScore::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_id');
    }

    public function getChangeDisplayAttribute(): string
    {
        return $this->change_amount > 0 ? "+{$this->change_amount}" : "{$this->change_amount}";
    }

    public function getChangeColorAttribute(): string
    {
        return $this->change_amount > 0 ? 'success' : 'danger';
    }
}
