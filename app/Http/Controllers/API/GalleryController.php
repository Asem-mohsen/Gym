<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{Branch, Gallery, SiteSetting, User};
use App\Services\GalleryService;
use Illuminate\Http\JsonResponse;
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
    public function index(SiteSetting $gym): JsonResponse
    {
        try {
            $galleries = $this->galleryService->getGalleriesForModel($gym);
            
            return successResponse($galleries, 'Galleries retrieved successfully');

        } catch (\Exception $e) {
            return failureResponse('Failed to retrieve galleries', 500);
        }
    }

    /**
     * Get all galleries for a specific branch
     */
    public function getBranchGalleries(SiteSetting $gym, Branch $branch): JsonResponse
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (!$this->hasAccessToBranch($branch, $user)) {
                return failureResponse('Access denied', 403);
            }

            $galleries = $this->galleryService->getGalleriesForModel($branch);
            
            return successResponse($galleries, 'Branch galleries retrieved successfully');

        } catch (\Exception $e) {
            return failureResponse('Failed to retrieve branch galleries', 500);
        }
    }

    /**
     * Get a specific gallery
     */
    public function show(SiteSetting $gym, Gallery $gallery): JsonResponse
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (!$this->hasAccessToGallery($gallery, $user)) {
                return failureResponse('Access denied', 403);
            }
            $gallery = $this->galleryService->getGalleryById($gallery->id, ['media']);

            return successResponse($gallery, 'Gallery retrieved successfully');

        } catch (\Exception $e) {
            return failureResponse('Failed to retrieve gallery', 500);
        }
    }


    /**
     * Check if user has access to branch
     */
    private function hasAccessToBranch(Branch $branch,User $user): bool
    {
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
    private function hasAccessToGallery(Gallery $gallery, User $user): bool
    {
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