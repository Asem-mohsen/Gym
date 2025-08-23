<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BranchScoreReviewRequest extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'requested_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'scheduled_review_date' => 'datetime',
        'is_approved' => 'boolean',
        'is_reviewed' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('supporting_documents');
    }

    public function branchScore(): BelongsTo
    {
        return $this->belongsTo(BranchScore::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_id');
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_reviewed) return 'pending';
        return $this->is_approved ? 'approved' : 'rejected';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        };
    }
}
