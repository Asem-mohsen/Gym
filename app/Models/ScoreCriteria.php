<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ScoreCriteria extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = ['id'];

    public $translatable = ['name', 'description'];

    protected $table = 'score_criteria';
    
    protected $casts = [
        'points' => 'integer',
        'is_active' => 'boolean',
        'is_negative' => 'boolean',
    ];

    public function branchScoreItems(): HasMany
    {
        return $this->hasMany(BranchScoreItem::class);
    }

    public function getPointsDisplayAttribute(): string
    {
        return $this->points > 0 ? "+{$this->points}" : "{$this->points}";
    }

    public function getTypeColorAttribute(): string
    {
        return $this->is_negative ? 'danger' : 'success';
    }
}
