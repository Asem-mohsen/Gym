<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Register media collections for the gallery
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->useDisk('public');
    }

    /**
     * Get the parent galleryable model (SiteSetting or Branch)
     */
    public function galleryable(): MorphTo
    {
        return $this->morphTo();
    }

    public function siteSetting(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    /**
     * Scope to get only active galleries
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Scope to filter by site setting
     */
    public function scopeForSiteSetting($query, $siteSettingId)
    {
        return $query->where('site_setting_id', $siteSettingId);
    }

    /**
     * Get all media with custom properties
     */
    public function getGalleryImages()
    {
        return $this->getMedia('gallery_images')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'title' => $media->getCustomProperty('title', ''),
                'alt_text' => $media->getCustomProperty('alt_text', ''),
                'caption' => $media->getCustomProperty('caption', ''),
                'file_name' => $media->file_name,
                'size' => $media->size,
                'created_at' => $media->created_at,
            ];
        });
    }
} 