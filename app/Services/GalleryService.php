<?php

namespace App\Services;

use App\Models\Gallery;
use App\Repositories\GalleryRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GalleryService
{
    public function __construct(protected GalleryRepository $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * Get all galleries for a specific model
     */
    public function getGalleriesForModel(Model $model , int $limit = 10): Collection
    {
        return $this->galleryRepository->getGalleriesForModel($model , $limit);
    }

    /**
     * Get all galleries for a site setting
     */
    public function getGalleriesForSiteSetting(int $siteSettingId, int $limit = 10): Collection
    {
        return $this->galleryRepository->getGalleriesForSiteSetting($siteSettingId, $limit);
    }

    /**
     * Get a specific gallery by ID
     */
    public function getGalleryById(int $id, array $with = []): ?Gallery
    {
        return $this->galleryRepository->findById($id, $with);
    }

    /**
     * Create a new gallery directly with site_setting_id
     */
    public function createGalleryDirectly(array $data, array $mediaFiles = []): Gallery
    {
        return DB::transaction(function () use ($data, $mediaFiles) {
            $gallery = $this->galleryRepository->createGalleryDirectly($data);

            // Add media files if provided
            foreach ($mediaFiles as $mediaData) {
                if (isset($mediaData['file']) && $mediaData['file'] instanceof UploadedFile) {
                    $customProperties = [
                        'title' => $mediaData['title'] ?? '',
                        'alt_text' => $mediaData['alt_text'] ?? '',
                        'caption' => $mediaData['caption'] ?? '',
                    ];

                    $this->galleryRepository->addMediaToGallery($gallery, $mediaData['file'], $customProperties);
                }
            }

            return $gallery->load('media');
        });
    }

    /**
     * Update an existing gallery
     */
    public function updateGallery(Gallery $gallery, array $data): Gallery
    {
        return $this->galleryRepository->updateGallery($gallery, $data);
    }

    /**
     * Delete a gallery
     */
    public function deleteGallery(Gallery $gallery): bool
    {
        return $this->galleryRepository->deleteGallery($gallery);
    }

    /**
     * Add media to an existing gallery
     */
    public function addMediaToGallery(Gallery $gallery, UploadedFile $file, array $customProperties = []): void
    {
        $this->galleryRepository->addMediaToGallery($gallery, $file, $customProperties);
    }

    /**
     * Remove media from a gallery
     */
    public function removeMediaFromGallery(Gallery $gallery, int $mediaId): bool
    {
        return $this->galleryRepository->removeMediaFromGallery($gallery, $mediaId);
    }
    
    /**
     * Get gallery statistics
     */
    public function getGalleryStats(Model $model): array
    {
        $galleries = $this->galleryRepository->getGalleriesForModel($model);
        
        $totalImages = 0;
        $totalSize = 0;
        
        foreach ($galleries as $gallery) {
            $media = $gallery->getMedia('gallery_images');
            $totalImages += $media->count();
            $totalSize += $media->sum('size');
        }

        return [
            'total_galleries' => $galleries->count(),
            'total_images' => $totalImages,
            'total_size_mb' => round($totalSize / (1024 * 1024), 2),
        ];
    }
} 