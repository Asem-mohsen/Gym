<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GymSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'site_setting_id',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'font_family',
        'border_radius',
        'box_shadow',
        'home_page_sections',
        'section_styles',
        'media_settings',
    ];

    protected $casts = [
        'home_page_sections' => 'array',
        'section_styles' => 'array',
        'media_settings' => 'array',
    ];

    /**
     * Get the site setting that owns the gym setting.
     */
    public function siteSetting(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    /**
     * Get all branding fields
     */
    public static function getBrandingFields(): array
    {
        return [
            'primary_color',
            'secondary_color',
            'accent_color',
            'text_color',
            'font_family',
            'border_radius',
            'box_shadow'
        ];
    }

    /**
     * Get default home page sections
     */
    public static function getDefaultHomePageSections(): array
    {
        return [
            'hero' => true,
            'choseus' => true,
            'classes' => true,
            'banner' => true,
            'memberships' => true,
            'gallery' => true,
            'team' => true
        ];
    }

    /**
     * Get available media types
     */
    public static function getAvailableMediaTypes(): array
    {
        return [
            'hero_banner',
            'choseus_banner',
            'classes_banner',
            'banner_section_bg',
            'memberships_banner',
            'gallery_banner',
            'team_banner'
        ];
    }

    /**
     * Get media types that support multiple files
     */
    public static function getMultipleFileMediaTypes(): array
    {
        return [
            'hero_banner' => 2, // 2 images for hero slider
            'choseus_banner' => 1,
            'classes_banner' => 1,
            'banner_section_bg' => 1,
            'memberships_banner' => 1,
            'gallery_banner' => 1,
            'team_banner' => 1
        ];
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $mediaTypes = self::getAvailableMediaTypes();
        $multipleFileTypes = self::getMultipleFileMediaTypes();
        
        foreach ($mediaTypes as $mediaType) {
            $collection = $this->addMediaCollection($mediaType)
                ->useDisk('public');
            
            // Set single file for types that only need one image
            if (!isset($multipleFileTypes[$mediaType]) || $multipleFileTypes[$mediaType] <= 1) {
                $collection->singleFile();
            }
        }
    }

    /**
     * Get only the non-null branding values
     */
    public function getNonNullBrandingValues(): array
    {
        $brandingFields = self::getBrandingFields();
        $values = [];
        
        foreach ($brandingFields as $field) {
            if ($this->$field !== null) {
                $values[$field] = $this->$field;
            }
        }
        
        return $values;
    }
}
