<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_internal' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('document')->singleFile();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function siteSettings(): BelongsToMany
    {
        return $this->belongsToMany(SiteSetting::class, 'document_site_setting');
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('document');
    }

    public function getDocumentNameAttribute(): ?string
    {
        $media = $this->getFirstMedia('document');
        return $media ? $media->name : null;
    }

    public function getDocumentSizeAttribute(): ?string
    {
        $media = $this->getFirstMedia('document');
        return $media ? $this->formatBytes($media->size) : null;
    }

    private function formatBytes($size, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getAvailableGymsCountAttribute(): string
    {
        $count = $this->siteSettings()->count();
        return $count > 0 ? (string) $count : 'All Gyms';
    }

    public function isAvailableToAllGyms(): bool
    {
        return $this->siteSettings()->count() === 0;
    }
}
