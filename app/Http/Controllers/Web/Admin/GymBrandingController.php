<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GymBranding\UpdateGymBrandingRequest;
use App\Models\{GymSetting, SiteSetting};
use App\Services\GymBrandingService;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\View\View;


class GymBrandingController extends Controller
{
    public function __construct(
        protected GymBrandingService $gymBrandingService
    ) {}

    /**
     * Show the branding settings page
     */
    public function show(int $siteSettingId): View
    {
        $siteSetting = SiteSetting::findOrFail($siteSettingId);
        $branding = $this->gymBrandingService->getBrandingForAdmin($siteSettingId);
        
        $defaultSections = GymSetting::getDefaultHomePageSections();
        $mediaTypes = GymSetting::getAvailableMediaTypes();
        $pageTypes = GymSetting::getAvailablePageTypes();

        $brandingData = $branding['branding'] ?? [];
        
        return view('admin.gym-branding.show', compact('siteSetting', 'brandingData', 'defaultSections', 'mediaTypes', 'pageTypes'));
    }

    /**
     * Update branding settings
     */
    public function update(UpdateGymBrandingRequest $request, int $siteSettingId): JsonResponse
    {
        try {
            $brandingData = $request->validated();
            $formType = $request->input('form_type', 'colors_typography');
            
            // Handle different form types
            switch ($formType) {
                case 'colors_typography':
                    $dataToUpdate = array_intersect_key($brandingData, array_flip([
                        'primary_color', 'secondary_color', 'accent_color', 'text_color',
                        'font_family', 'border_radius', 'box_shadow'
                    ]));
                    
                    // Remove empty color values to avoid validation issues
                    foreach (['primary_color', 'secondary_color', 'accent_color', 'text_color'] as $colorField) {
                        if (isset($dataToUpdate[$colorField]) && empty($dataToUpdate[$colorField])) {
                            unset($dataToUpdate[$colorField]);
                        }
                    }
                    break;
                    
                case 'page_sections':
                    $dataToUpdate = array_intersect_key($brandingData, array_flip([
                        'home_page_sections', 'section_styles'
                    ]));
                    break;
                    
                case 'media_settings':
                    // For media settings, we need to handle the files properly
                    $mediaSettings = $brandingData['media_settings'] ?? [];
                    $multipleFileTypes = GymSetting::getMultipleFileMediaTypes();
                    
                    // Process media settings to ensure correct structure
                    foreach ($mediaSettings as $mediaType => $files) {
                        if (empty($files)) continue;
                        
                        $maxFiles = $multipleFileTypes[$mediaType] ?? 1;
                        
                        if ($maxFiles > 1) {
                            // Ensure it's an array for multiple files
                            if (!is_array($files)) {
                                $mediaSettings[$mediaType] = [$files];
                            }
                        } else {
                            // Ensure it's a single file for single file types
                            if (is_array($files)) {
                                $mediaSettings[$mediaType] = $files[0] ?? null;
                            }
                        }
                    }
                    
                    $dataToUpdate = ['media_settings' => $mediaSettings];
                    break;
                    
                case 'page_texts':
                    // Handle page text updates
                    $pageType = $request->input('page_type');
                    $texts = $brandingData['texts'] ?? [];
                    
                    if (!$pageType) {
                        throw new \InvalidArgumentException('Page type is required for text updates');
                    }
                    
                    $updatedGymSetting = $this->gymBrandingService->updatePageTexts($siteSettingId, $pageType, $texts);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Page texts updated successfully',
                        'data' => $updatedGymSetting
                    ]);
                    
                default:
                    $dataToUpdate = $brandingData;
            }
            
            $updatedGymSetting = $this->gymBrandingService->updateGymBranding($siteSettingId, $dataToUpdate);

            return response()->json([
                'success' => true,
                'message' => 'Branding settings updated successfully',
                'data' => $updatedGymSetting
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update branding settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset branding to defaults
     */
    public function reset(int $siteSettingId): JsonResponse
    {
        try {
            $deleted = $this->gymBrandingService->resetToDefaults($siteSettingId);

            return response()->json([
                'success' => true,
                'message' => 'Branding settings reset to defaults successfully',
                'deleted' => $deleted
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset branding settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get CSS variables for current gym
     */
    public function getCssVariables(int $siteSettingId): JsonResponse
    {
        try {
            $cssVariables = $this->gymBrandingService->generateCssVariables($siteSettingId);

            return response()->json([
                'success' => true,
                'data' => [
                    'css_variables' => $cssVariables
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate CSS variables: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview branding changes
     */
    public function preview(Request $request): JsonResponse
    {
        try {
            $brandingData = $request->only([
                'primary_color', 'secondary_color', 'accent_color', 'text_color',
                'background_color', 'success_color', 'warning_color', 'danger_color', 'info_color',
                'font_family', 'border_radius', 'box_shadow'
            ]);

            // Create a temporary site setting for preview
            $tempSiteSetting = new SiteSetting($brandingData);
            
            $cssVariables = [];
            foreach ($brandingData as $key => $value) {
                if ($value) {
                    $cssKey = str_replace('_', '-', $key);
                    $cssVariables[] = "--color-{$cssKey}: {$value};";
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'css_variables' => implode("\n    ", $cssVariables),
                    'preview_data' => $brandingData
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate preview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get page texts for a specific page
     */
    public function getPageTexts(int $siteSettingId, string $pageType): JsonResponse
    {
        try {
            $texts = $this->gymBrandingService->getPageTexts($siteSettingId, $pageType);

            return response()->json([
                'success' => true,
                'data' => [
                    'page_type' => $pageType,
                    'texts' => $texts
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get page texts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview page texts
     */
    public function previewPageTexts(Request $request): JsonResponse
    {
        try {
            $pageType = $request->input('page_type');
            $customTexts = $request->input('texts', []);

            if (!$pageType) {
                throw new \InvalidArgumentException('Page type is required');
            }

            $preview = $this->gymBrandingService->previewPageTexts($pageType, $customTexts);

            return response()->json([
                'success' => true,
                'data' => $preview
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to preview page texts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset page texts to defaults
     */
    public function resetPageTexts(int $siteSettingId, ?string $pageType = null): JsonResponse
    {
        try {
            $reset = $this->gymBrandingService->resetPageTexts($siteSettingId, $pageType);

            return response()->json([
                'success' => true,
                'message' => $pageType 
                    ? "Page texts for {$pageType} reset to defaults successfully"
                    : 'All page texts reset to defaults successfully',
                'reset' => $reset
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset page texts: ' . $e->getMessage()
            ], 500);
        }
    }
}
