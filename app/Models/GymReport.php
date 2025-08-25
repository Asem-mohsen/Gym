<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'report_sections',
        'date_from',
        'date_to',
        'export_format',
        'status',
        'generated_by',
    ];

    protected $casts = [
        'report_sections' => 'array',
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'gym_id');
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
