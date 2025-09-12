<?php

namespace App\Services;

use App\Models\GymSetting;
use App\Repositories\{GymBrandingRepository, SiteSettingRepository};
use Illuminate\Support\Facades\{Cache, DB, Log};

class GymBrandingService
{
    public function __construct(
        protected GymBrandingRepository $gymBrandingRepository,
        protected SiteSettingRepository $siteSettingRepository
    ) {}

    /**
     * Get branding settings for current gym
     */
    public function getCurrentGymBranding(int $siteSettingId): array
    {
        if (!$siteSettingId) {
            $siteSettingId = $this->getCurrentSiteSettingId();
        }

        $cacheKey = "gym_branding_{$siteSettingId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($siteSettingId) {
            $gymSetting = $this->gymBrandingRepository->getBrandingSettings($siteSettingId);
            
            if (!$gymSetting) {
                return [];
            }

            return $gymSetting->getNonNullBrandingValues();
        });
    }


    /**
     * Update branding settings for a gym
     */
    public function updateGymBranding(int $siteSettingId, array $brandingData): GymSetting
    {
        return DB::transaction(function () use ($siteSettingId, $brandingData) {
            // Validate color formats
            $this->validateColorFormats($brandingData);
            
            // Handle media uploads if present
            $mediaSettings = $brandingData['media_settings'] ?? [];
            unset($brandingData['media_settings']); // Remove from data to avoid saving as JSON
            
            // Create or update the branding settings
            $updatedGymSetting = $this->gymBrandingRepository->createOrUpdateBrandingSettings($siteSettingId, $brandingData);
            
            if (!empty($mediaSettings)) {
                $this->handleMediaUploads($updatedGymSetting, $mediaSettings);
            }
            
            // Clear cache
            $this->clearBrandingCache($siteSettingId);
            
            return $updatedGymSetting;
        });
    }

    /**
     * Generate CSS variables for the gym branding
     */
    public function generateCssVariables(?int $siteSettingId = null): string
    {
        $branding = $this->getCurrentGymBranding($siteSettingId);
        
        $cssVariables = [];
        foreach ($branding as $key => $value) {
            if ($value && !in_array($key, ['media_urls'])) { // Exclude media_urls from CSS variables
                $cssKey = str_replace('_', '-', $key);
                $cssVariables[] = "--color-{$cssKey}: {$value};";
            }
        }
        
        return implode("\n    ", $cssVariables);
    }

    /**
     * Get branding settings for admin panel
     */
    public function getBrandingForAdmin(int $siteSettingId): array
    {
        $siteSetting = $this->siteSettingRepository->findById($siteSettingId);
        
        if (!$siteSetting) {
            throw new \Exception("Site setting not found.", 404);
        }

        // Force clear cache to get fresh data
        $this->clearBrandingCache($siteSettingId);
        
        $branding = $this->gymBrandingRepository->getBrandingSettings($siteSettingId);
        
        $brandingData = $branding ? $branding->getNonNullBrandingValues() : [];
        
        // Add media URLs if branding exists
        if ($branding) {
            $mediaUrls = $this->getMediaUrls($branding);
            $brandingData['media_urls'] = $mediaUrls;
            
            $brandingData['page_texts'] = $branding->getPageTexts();
            $brandingData['repeater_fields'] = $branding->getAllRepeaterData();
        } else {
            $brandingData['media_urls'] = [];
            $brandingData['page_texts'] = GymSetting::getDefaultPageTexts();
            $brandingData['repeater_fields'] = [];
        }

        return [
            'site_setting' => $siteSetting,
            'branding' => $brandingData,
            'has_custom_branding' => $branding ? $this->gymBrandingRepository->hasCustomBranding($branding) : false
        ];
    }

    /**
     * Reset branding to defaults (delete all custom branding)
     */
    public function resetToDefaults(int $siteSettingId): bool
    {
        $deleted = $this->gymBrandingRepository->deleteBrandingSettings($siteSettingId);
        
        // Clear cache
        $this->clearBrandingCache($siteSettingId);
        
        return $deleted;
    }

    /**
     * Get current site setting ID
     */
    protected function getCurrentSiteSettingId(): int
    {
        // This should be implemented based on your current gym context logic
        // For now, returning a default or getting from session/request
        return request()->session()->get('current_gym_id', 1);
    }


    /**
     * Validate color formats
     */
    protected function validateColorFormats(array $brandingData): void
    {
        $colorFields = [
            'primary_color', 'secondary_color', 'accent_color', 'text_color'
        ];

        foreach ($colorFields as $field) {
            if (isset($brandingData[$field]) && !empty($brandingData[$field])) {
                if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $brandingData[$field])) {
                    throw new \InvalidArgumentException("Invalid color format for {$field}. Use hex format (e.g., #ff0000)");
                }
            }
        }
    }

    /**
     * Clear branding cache for a specific gym
     */
    protected function clearBrandingCache(int $siteSettingId): void
    {
        Cache::forget("gym_branding_{$siteSettingId}");
    }

    /**
     * Handle media uploads using Spatie Media Library
     */
    protected function handleMediaUploads(GymSetting $gymSetting, array $mediaSettings): void
    {
        $multipleFileTypes = GymSetting::getMultipleFileMediaTypes();
        
        foreach ($mediaSettings as $mediaType => $files) {
            if (empty($files)) continue;
            
            // Clear existing media for this type
            $gymSetting->clearMediaCollection($mediaType);
            
            $maxFiles = $multipleFileTypes[$mediaType] ?? 1;
            
            // Ensure files is always an array for processing
            if (!is_array($files)) {
                $files = [$files];
            }
            
            if ($maxFiles > 1) {
                // Handle multiple files
                foreach ($files as $index => $file) {
                    if ($index >= $maxFiles) break; // Limit to max files
                    if ($file && $file->isValid()) {
                        $gymSetting->addMedia($file)
                            ->toMediaCollection($mediaType);
                    }
                }
            } else {
                // Handle single file (take the first one)
                $file = $files[0] ?? null;
                if ($file && $file->isValid()) {
                    $gymSetting->addMedia($file)
                        ->toMediaCollection($mediaType);
                }
            }
        }
    }

    /**
     * Get media URLs for a gym setting
     */
    public function getMediaUrls(GymSetting $gymSetting): array
    {
        $mediaTypes = GymSetting::getAvailableMediaTypes();
        $multipleFileTypes = GymSetting::getMultipleFileMediaTypes();
        $mediaUrls = [];
        
        foreach ($mediaTypes as $mediaType) {
            $maxFiles = $multipleFileTypes[$mediaType] ?? 1;
            
            if ($maxFiles > 1) {
                // Get all media for this type
                $mediaCollection = $gymSetting->getMedia($mediaType);
                $urls = [];
                foreach ($mediaCollection as $media) {
                    $urls[] = $media->getUrl();
                }
                $mediaUrls[$mediaType] = $urls;
            } else {
                // Get single media
                $media = $gymSetting->getFirstMedia($mediaType);
                $mediaUrls[$mediaType] = $media ? $media->getUrl() : null;
            }
        }
        
        return $mediaUrls;
    }

    /**
     * Update page texts for a specific page
     */
    public function updatePageTexts(int $siteSettingId, string $pageType, array $texts): GymSetting
    {
        return DB::transaction(function () use ($siteSettingId, $pageType, $texts) {
            // Get current branding settings
            $gymSetting = $this->gymBrandingRepository->getBrandingSettings($siteSettingId);
            
            if (!$gymSetting) {
                // Create new gym setting if it doesn't exist
                $gymSetting = $this->gymBrandingRepository->createOrUpdateBrandingSettings($siteSettingId, []);
            }
            
            // Get current page texts
            $currentPageTexts = $gymSetting->page_texts ?? [];
            
            // Update texts for the specific page
            $currentPageTexts[$pageType] = array_merge($currentPageTexts[$pageType] ?? [], $texts);
            
            // Update the gym setting
            $gymSetting->update(['page_texts' => $currentPageTexts]);
            
            // Clear cache
            $this->clearBrandingCache($siteSettingId);
            
            return $gymSetting;
        });
    }

    /**
     * Get page texts for a specific page
     */
    public function getPageTexts(int $siteSettingId, ?string $pageType = null): array
    {
        $gymSetting = $this->gymBrandingRepository->getBrandingSettings($siteSettingId);
        
        if (!$gymSetting) {
            $defaultTexts = GymSetting::getDefaultPageTexts();
            return $pageType ? ($defaultTexts[$pageType] ?? []) : $defaultTexts;
        }
        
        if ($pageType) {
            return $gymSetting->getPageText($pageType);
        }
        
        return $gymSetting->getPageTexts();
    }

    /**
     * Reset page texts to defaults
     */
    public function resetPageTexts(int $siteSettingId, ?string $pageType = null): bool
    {
        return DB::transaction(function () use ($siteSettingId, $pageType) {
            $gymSetting = $this->gymBrandingRepository->getBrandingSettings($siteSettingId);
            
            if (!$gymSetting) {
                return false;
            }
            
            $currentPageTexts = $gymSetting->page_texts ?? [];
            
            if ($pageType) {
                // Reset specific page
                unset($currentPageTexts[$pageType]);
            } else {
                // Reset all pages
                $currentPageTexts = [];
            }
            
            $gymSetting->update(['page_texts' => $currentPageTexts]);
            
            // Clear cache
            $this->clearBrandingCache($siteSettingId);
            
            return true;
        });
    }

    /**
     * Preview page texts with custom values
     */
    public function previewPageTexts(string $pageType, array $customTexts): array
    {
        $defaultTexts = GymSetting::getDefaultPageTexts();
        $pageDefaults = $defaultTexts[$pageType] ?? [];
        
        // Merge custom texts with defaults
        $previewTexts = array_merge($pageDefaults, $customTexts);
        
        return [
            'page_type' => $pageType,
            'texts' => $previewTexts,
            'defaults' => $pageDefaults
        ];
    }

    /**
     * Update repeater fields for a specific section
     */
    public function updateRepeaterFields(int $siteSettingId, string $section, array $data): GymSetting
    {
        return DB::transaction(function () use ($siteSettingId, $section, $data) {
            // Get current branding settings
            $gymSetting = $this->gymBrandingRepository->getBrandingSettings($siteSettingId);
            
            if (!$gymSetting) {
                // Create new gym setting if it doesn't exist
                $gymSetting = $this->gymBrandingRepository->createOrUpdateBrandingSettings($siteSettingId, []);
            }
            
            // Update repeater data for the specific section
            $gymSetting->updateRepeaterData($section, $data);
            
            // Clear cache
            $this->clearBrandingCache($siteSettingId);
            
            return $gymSetting;
        });
    }

    /**
     * Get repeater fields for a specific section
     */
    public function getRepeaterFields(int $siteSettingId, ?string $section = null): array
    {
        $gymSetting = $this->gymBrandingRepository->getBrandingSettings($siteSettingId);
        
        if (!$gymSetting) {
            $defaultConfigs = GymSetting::getDefaultRepeaterConfigs();
            if ($section) {
                return $defaultConfigs[$section]['default_items'] ?? [];
            }
            $result = [];
            foreach ($defaultConfigs as $sectionKey => $config) {
                $result[$sectionKey] = $config['default_items'] ?? [];
            }
            return $result;
        }
        
        if ($section) {
            return $gymSetting->getRepeaterData($section);
        }
        
        return $gymSetting->getAllRepeaterData();
    }

    /**
     * Reset repeater fields to defaults
     */
    public function resetRepeaterFields(int $siteSettingId, ?string $section = null): bool
    {
        return DB::transaction(function () use ($siteSettingId, $section) {
            $gymSetting = $this->gymBrandingRepository->getBrandingSettings($siteSettingId);
            
            if (!$gymSetting) {
                return false;
            }
            
            $currentRepeaterFields = $gymSetting->repeater_fields ?? [];
            
            if ($section) {
                // Reset specific section
                unset($currentRepeaterFields[$section]);
            } else {
                // Reset all sections
                $currentRepeaterFields = [];
            }
            
            $gymSetting->update(['repeater_fields' => $currentRepeaterFields]);
            
            // Clear cache
            $this->clearBrandingCache($siteSettingId);
            
            return true;
        });
    }

    /**
     * Preview repeater fields with custom values
     */
    public function previewRepeaterFields(string $section, array $customData): array
    {
        $defaultConfigs = GymSetting::getDefaultRepeaterConfigs();
        $config = $defaultConfigs[$section] ?? null;
        
        if (!$config) {
            return [
                'section' => $section,
                'data' => $customData,
                'config' => null
            ];
        }
        
        return [
            'section' => $section,
            'data' => $customData,
            'config' => $config,
            'defaults' => $config['default_items'] ?? []
        ];
    }
}
