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

        $brandingData = $branding['branding'] ?? [];
        
        return view('admin.gym-branding.show', compact('siteSetting', 'brandingData', 'defaultSections', 'mediaTypes'));
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
}
