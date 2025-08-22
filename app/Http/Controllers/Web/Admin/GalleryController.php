<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gallery\{CreateGalleryRequest, UpdateGalleryRequest};
use App\Models\Gallery;
use App\Services\GalleryService;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    public function __construct(protected GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    /**
     * Display the main gallery page
     */
    public function index()
    {
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        $galleries = $this->galleryService->getGalleriesForSiteSetting($siteSetting->id);
        $stats = $this->galleryService->getGalleryStats($siteSetting);

        return view('admin.galleries.index', compact('galleries', 'stats', 'siteSetting'));
    }

    public function create()
    {
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        return view('admin.galleries.create', compact('siteSetting'));
    }

    public function edit(Gallery $gallery)
    {
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }
        
        if ($gallery->site_setting_id !== $siteSetting->id) {
            abort(404, 'Gallery not found');
        }

        return view('admin.galleries.edit', compact('gallery', 'siteSetting'));
    }

    public function store(CreateGalleryRequest $request)
    {
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        $data = $request->only(['title', 'description', 'is_active', 'sort_order']);
        
        $data['site_setting_id'] = $siteSetting->id;
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
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        if ($gallery->site_setting_id !== $siteSetting->id) {
            abort(404, 'Gallery not found');
        }

        $data = $request->only(['title', 'description', 'is_active', 'sort_order']);

        // Handle new image uploads
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
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        $this->galleryService->deleteGallery($gallery);

        return redirect()->route('galleries.index')->with('success', 'Gallery deleted successfully');
    }

    public function removeMedia(int $galleryId, int $mediaId)
    {
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        $gallery = $this->galleryService->getGalleryById($galleryId);
        
        if (!$gallery || $gallery->site_setting_id !== $siteSetting->id) {
            abort(404, 'Gallery not found');
        }

        $this->galleryService->removeMediaFromGallery($gallery, $mediaId);

        return redirect()->back()->with('success', 'Image removed successfully');
    }
}
