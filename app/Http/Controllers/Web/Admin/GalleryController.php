<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Gallery;
use App\Models\SiteSetting;
use App\Services\GalleryService;
use Illuminate\Http\Request;
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

        $galleries = $this->galleryService->getGalleriesForModel($siteSetting);
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

    public function edit(int $id)
    {
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        $gallery = $this->galleryService->getGalleryById($id);
        
        if (!$gallery || $gallery->galleryable_id !== $siteSetting->id) {
            abort(404, 'Gallery not found');
        }

        return view('admin.galleries.edit', compact('gallery', 'siteSetting'));
    }

    public function store(Request $request)
    {
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $data = $request->only(['title', 'description', 'is_active', 'sort_order']);
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

        $this->galleryService->createGallery($data, $siteSetting, $mediaFiles);

        return redirect()->route('galleries.index')->with('success', 'Gallery created successfully');
    }

    public function update(Request $request, int $id)
    {
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        $gallery = $this->galleryService->getGalleryById($id);
        
        if (!$gallery || $gallery->galleryable_id !== $siteSetting->id) {
            abort(404, 'Gallery not found');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

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

    public function destroy(int $id)
    {
        $siteSetting = Auth::user()->site;
        
        if (!$siteSetting) {
            abort(404, 'Site settings not found for this admin');
        }

        $gallery = $this->galleryService->getGalleryById($id);
        
        if (!$gallery || $gallery->galleryable_id !== $siteSetting->id) {
            abort(404, 'Gallery not found');
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
        
        if (!$gallery || $gallery->galleryable_id !== $siteSetting->id) {
            abort(404, 'Gallery not found');
        }

        $this->galleryService->removeMediaFromGallery($gallery, $mediaId);

        return redirect()->back()->with('success', 'Image removed successfully');
    }
    
}
