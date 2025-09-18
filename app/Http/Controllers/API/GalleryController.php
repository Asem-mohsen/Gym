<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryResource;
use App\Models\SiteSetting;
use App\Services\GalleryService;
use Illuminate\Http\JsonResponse;

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
            $gallery = $this->galleryService->getGalleriesForPage($gym->id, 'home');
            
            return successResponse(GalleryResource::collection($gallery), 'Gallery retrieved successfully');

        } catch (Exception $e) {
            return failureResponse('Failed to retrieve gallery', 500);
        }
    }
} 