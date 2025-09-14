<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\{CreateGalleryRequest, UpdateGalleryRequest};
use App\Models\{Gallery};
use App\Services\{GalleryService, SiteSettingService};

class GalleryController extends Controller
{
    protected int $siteSettingId;
    public function __construct(protected GalleryService $galleryService, protected SiteSettingService $siteSettingService)
    {
        $this->galleryService = $galleryService;
        $this->siteSettingId = $this->siteSettingService->getCurrentSiteSettingId();
    }

    /**
     * Display the main gallery page
     */
    public function index()
    {
        $galleries = $this->galleryService->getGalleriesForSiteSetting($this->siteSettingId);

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function store(CreateGalleryRequest $request)
    {
        $data = $request->only(['title', 'description', 'is_active', 'sort_order', 'pages']);
        
        $data['is_active'] = (bool) $data['is_active'];
        
        $data['site_setting_id'] = $this->siteSettingId;
        $mediaFiles = [];

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $mediaFiles[] = [
                    'file' => $file,
                    'title' => $file->getClientOriginalName(),
                    'alt_text' => $file->getClientOriginalName(),
                    'caption' => '',
                ];
            }
        }

        $this->galleryService->createGalleryDirectly($data, $mediaFiles);

        return redirect()->route('galleries.index')->with('success', 'Gallery created successfully');
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery)
    {
        $data = $request->only(['title', 'description', 'is_active', 'sort_order', 'pages']);
        
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $customProperties = [
                    'title' => $file->getClientOriginalName(),
                    'alt_text' => $file->getClientOriginalName(),
                    'caption' => '',
                ];

                $this->galleryService->addMediaToGallery($gallery, $file, $customProperties);
            }
        }

        $this->galleryService->updateGallery($gallery, $data);

        return redirect()->route('galleries.index')->with('success', 'Gallery updated successfully');
    }

    public function destroy(Gallery $gallery)
    {
        $this->galleryService->deleteGallery($gallery);

        return redirect()->route('galleries.index')->with('success', 'Gallery deleted successfully');
    }

    public function removeMedia(int $galleryId, int $mediaId)
    {
        $gallery = $this->galleryService->getGalleryById($galleryId);
        
        if (!$gallery || $gallery->site_setting_id !== $this->siteSettingId) {
            abort(404, 'Gallery not found');
        }

        $this->galleryService->removeMediaFromGallery($gallery, $mediaId);

        return redirect()->back()->with('success', 'Image removed successfully');
    }
}
