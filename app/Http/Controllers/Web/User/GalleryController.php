<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\{Branch, SiteSetting};
use App\Services\GalleryService;

class GalleryController extends Controller
{
    public function __construct(protected GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    /**
     * Display the main gallery page
     */
    public function index(SiteSetting $siteSetting)
    {
        $galleries = $this->galleryService->getGalleriesForModel($siteSetting);

        return view('user.gallery', compact('galleries', 'siteSetting'));
    }

    /**
     * Display a specific gallery
     */
    public function show(SiteSetting $siteSetting, int $id)
    {
        $gallery = $this->galleryService->getGalleryById($id);
        
        if (!$gallery || !$gallery->is_active || $gallery->site_setting_id !== $siteSetting->id) {
            abort(404, 'Gallery not found');
        }

        $images = $gallery->getGalleryImages();

        return view('user.gallery.show', compact('gallery', 'images', 'siteSetting'));
    }

    /**
     * Display branch galleries
     */
    public function branchGalleries(SiteSetting $siteSetting, int $branchId)
    {
        $branch = Branch::where('site_setting_id', $siteSetting->id)->findOrFail($branchId);
        $galleries = $this->galleryService->getGalleriesForModel($branch);

        return view('user.gallery.branch', compact('branch', 'galleries', 'siteSetting'));
    }

    /**
     * Display branch gallery
     */
    public function branchGallery(SiteSetting $siteSetting, int $branchId, int $galleryId)
    {
        $branch = Branch::where('site_setting_id', $siteSetting->id)->findOrFail($branchId);
        $gallery = $this->galleryService->getGalleryById($galleryId);
        
        if (!$gallery || !$gallery->is_active || $gallery->site_setting_id !== $siteSetting->id) {
            abort(404, 'Gallery not found');
        }

        $images = $gallery->getGalleryImages();

        return view('user.gallery.branch-show', compact('branch', 'gallery', 'images', 'siteSetting'));
    }
    
}
