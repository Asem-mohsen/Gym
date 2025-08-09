<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\{CreateGalleryRequest, UpdateGalleryRequest};
use App\Models\{Branch, Gallery, SiteSetting, User};
use App\Services\GalleryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function __construct(protected GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    /**
     * Get all galleries for a site setting
     */
    public function getSiteGalleries(Request $request): JsonResponse
    {
        try {
            $siteSetting = $this->getCurrentSiteSetting();
            
            if (!$siteSetting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site setting not found'
                ], 404);
            }

            $galleries = $this->galleryService->getGalleriesForModel($siteSetting);
            
            return response()->json([
                'success' => true,
                'data' => $galleries,
                'message' => 'Galleries retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve galleries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all galleries for a specific branch
     */
    public function getBranchGalleries(Request $request, int $branchId): JsonResponse
    {
        try {
            $branch = Branch::findOrFail($branchId);
            
            // Check if user has access to this branch
            if (!$this->hasAccessToBranch($branch)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $galleries = $this->galleryService->getGalleriesForModel($branch);
            
            return response()->json([
                'success' => true,
                'data' => $galleries,
                'message' => 'Branch galleries retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve branch galleries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific gallery
     */
    public function show(int $id): JsonResponse
    {
        try {
            $gallery = $this->galleryService->getGalleryById($id);
            
            if (!$gallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gallery not found'
                ], 404);
            }

            // Check access
            if (!$this->hasAccessToGallery($gallery)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'gallery' => $gallery,
                    'images' => $gallery->getGalleryImages()
                ],
                'message' => 'Gallery retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new gallery for site setting
     */
    public function createSiteGallery(CreateGalleryRequest $request): JsonResponse
    {
        try {
            $siteSetting = $this->getCurrentSiteSetting();
            
            if (!$siteSetting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site setting not found'
                ], 404);
            }

            $data = $request->validated();
            $mediaFiles = $data['media_files'] ?? [];

            $gallery = $this->galleryService->createGallery($data, $siteSetting, $mediaFiles);

            return response()->json([
                'success' => true,
                'data' => $gallery,
                'message' => 'Gallery created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new gallery for a branch
     */
    public function createBranchGallery(CreateGalleryRequest $request, int $branchId): JsonResponse
    {
        try {
            $branch = Branch::findOrFail($branchId);
            
            if (!$this->hasAccessToBranch($branch)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $data = $request->validated();
            $mediaFiles = $data['media_files'] ?? [];

            $gallery = $this->galleryService->createGallery($data, $branch, $mediaFiles);

            return response()->json([
                'success' => true,
                'data' => $gallery,
                'message' => 'Branch gallery created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create branch gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a gallery
     */
    public function update(UpdateGalleryRequest $request, int $id): JsonResponse
    {
        try {
            $gallery = $this->galleryService->getGalleryById($id);
            
            if (!$gallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gallery not found'
                ], 404);
            }

            if (!$this->hasAccessToGallery($gallery)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $data = $request->validated();
            
            // Handle media properties updates
            if (isset($data['media_properties'])) {
                foreach ($data['media_properties'] as $mediaProperty) {
                    $properties = [
                        'title' => $mediaProperty['title'] ?? '',
                        'alt_text' => $mediaProperty['alt_text'] ?? '',
                        'caption' => $mediaProperty['caption'] ?? '',
                    ];
                    
                    $this->galleryService->updateMediaProperties(
                        $gallery, 
                        $mediaProperty['media_id'], 
                        $properties
                    );
                }
            }

            // Handle new media files
            if (isset($data['media_files'])) {
                foreach ($data['media_files'] as $mediaData) {
                    if (isset($mediaData['file'])) {
                        $customProperties = [
                            'title' => $mediaData['title'] ?? '',
                            'alt_text' => $mediaData['alt_text'] ?? '',
                            'caption' => $mediaData['caption'] ?? '',
                        ];

                        $this->galleryService->addMediaToGallery(
                            $gallery, 
                            $mediaData['file'], 
                            $customProperties
                        );
                    }
                }
            }

            // Remove media_files from data before updating gallery
            unset($data['media_files'], $data['media_properties']);
            
            $updatedGallery = $this->galleryService->updateGallery($gallery, $data);

            return response()->json([
                'success' => true,
                'data' => $updatedGallery,
                'message' => 'Gallery updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a gallery
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $gallery = $this->galleryService->getGalleryById($id);
            
            if (!$gallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gallery not found'
                ], 404);
            }

            if (!$this->hasAccessToGallery($gallery)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $this->galleryService->deleteGallery($gallery);

            return response()->json([
                'success' => true,
                'message' => 'Gallery deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove media from gallery
     */
    public function removeMedia(Request $request, int $galleryId, int $mediaId): JsonResponse
    {
        try {
            $gallery = $this->galleryService->getGalleryById($galleryId);
            
            if (!$gallery) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gallery not found'
                ], 404);
            }

            if (!$this->hasAccessToGallery($gallery)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $success = $this->galleryService->removeMediaFromGallery($gallery, $mediaId);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Media removed successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Media not found'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove media',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder galleries
     */
    public function reorder(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'gallery_ids' => 'required|array',
                'gallery_ids.*' => 'integer|exists:galleries,id'
            ]);

            $this->galleryService->reorderGalleries($request->gallery_ids);

            return response()->json([
                'success' => true,
                'message' => 'Galleries reordered successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder galleries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get gallery statistics
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $siteSetting = $this->getCurrentSiteSetting();
            
            if (!$siteSetting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site setting not found'
                ], 404);
            }

            $stats = $this->galleryService->getGalleryStats($siteSetting);

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Gallery statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve gallery statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current site setting
     */
    private function getCurrentSiteSetting(): ?SiteSetting
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }

        return $user->site ?? SiteSetting::where('owner_id', $user->id)->first();
    }

    /**
     * Check if user has access to branch
     */
    private function hasAccessToBranch(Branch $branch): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Admin can access any branch
        /** @var User $user */
        if ($user->isAdmin()) {
            return true;
        }

        // Check if user's site setting matches branch's site setting
        return $user->site_setting_id === $branch->site_setting_id;
    }

    /**
     * Check if user has access to gallery
     */
    private function hasAccessToGallery(Gallery $gallery): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Admin can access any gallery
         /** @var User $user */
        if ($user->isAdmin()) {
            return true;
        }

        // Check if gallery belongs to user's site setting
        if ($gallery->galleryable_type === SiteSetting::class) {
            return $user->site_setting_id === $gallery->galleryable_id;
        }

        // Check if gallery belongs to a branch in user's site setting
        if ($gallery->galleryable_type === Branch::class) {
            $branch = Branch::find($gallery->galleryable_id);
            return $branch && $user->site_setting_id === $branch->site_setting_id;
        }

        return false;
    }
} 