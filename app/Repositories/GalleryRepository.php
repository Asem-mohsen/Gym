<?php

namespace App\Repositories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class GalleryRepository
{
    /**
     * Get all galleries for a specific model (SiteSetting or Branch)
     */
    public function getGalleriesForModel(Model $model, int $limit = 10): Collection
    {
        return $model->galleries()
            ->with(['media' => fn($q) => $q->limit($limit)])
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get all galleries for a site setting
     */
    public function getGalleriesForSiteSetting(int $siteSettingId, int $limit = 10): Collection
    {
        return Gallery::where('site_setting_id', $siteSettingId)
            ->with(['media' => fn($q) => $q->limit($limit)])
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get a specific gallery by ID
     */
    public function findById(int $id, array $with = []): ?Gallery
    {
        return Gallery::with($with)->find($id);
    }

    /**
     * Create a new gallery
     */
    public function createGallery(array $data, Model $model): Gallery
    {
        $data['galleryable_type'] = get_class($model);
        $data['galleryable_id'] = $model->id;
        
        if (isset($model->site_setting_id)) {
            $data['site_setting_id'] = $model->site_setting_id;
        } elseif (method_exists($model, 'siteSetting')) {
            $data['site_setting_id'] = $model->siteSetting->id;
        }
        
        return Gallery::create($data);
    }

    /**
     * Create a new gallery directly with site_setting_id
     */
    public function createGalleryDirectly(array $data): Gallery
    {
        return Gallery::create($data);
    }

    /**
     * Update an existing gallery
     */
    public function updateGallery(Gallery $gallery, array $data): Gallery
    {
        $gallery->update($data);
        return $gallery->fresh();
    }

    /**
     * Delete a gallery
     */
    public function deleteGallery(Gallery $gallery): bool
    {
        return $gallery->delete();
    }

    /**
     * Add media to a gallery
     */
    public function addMediaToGallery(Gallery $gallery, UploadedFile $file, array $customProperties = []): void
    {
        $gallery->addMedia($file)
            ->withCustomProperties($customProperties)
            ->toMediaCollection('gallery_images');
    }

    /**
     * Remove media from a gallery
     */
    public function removeMediaFromGallery(Gallery $gallery, int $mediaId): bool
    {
        $media = $gallery->getMedia('gallery_images')->find($mediaId);
        
        if ($media) {
            $media->delete();
            return true;
        }
        
        return false;
    }

    /**
     * Update media custom properties
     */
    public function updateMediaProperties(Gallery $gallery, int $mediaId, array $properties): bool
    {
        $media = $gallery->getMedia('gallery_images')->find($mediaId);
        
        if ($media) {
            $media->setCustomProperties($properties);
            $media->save();
            return true;
        }
        
        return false;
    }

    /**
     * Reorder galleries
     */
    public function reorderGalleries(array $galleryIds): void
    {
        foreach ($galleryIds as $index => $galleryId) {
            Gallery::where('id', $galleryId)->update(['sort_order' => $index]);
        }
    }

    /**
     * Get galleries with pagination
     */
    public function getGalleriesPaginated(Model $model, int $perPage = 10)
    {
        return $model->galleries()
            ->with('media')
            ->active()
            ->ordered()
            ->paginate($perPage);
    }

    /**
     * Search galleries by title
     */
    public function searchGalleries(Model $model, string $search): Collection
    {
        return $model->galleries()
            ->with('media')
            ->where('title', 'like', "%{$search}%")
            ->active()
            ->ordered()
            ->get();
    }
} 