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
     * Get a specific gallery by ID
     */
    public function getGalleryById(int $id): ?Gallery
    {
        return $this->galleryRepository->findById($id);
    }

    /**
     * Create a new gallery with media
     */
    public function createGallery(array $data, Model $model, array $mediaFiles = []): Gallery
    {
        return DB::transaction(function () use ($data, $model, $mediaFiles) {
            $gallery = $this->galleryRepository->createGallery($data, $model);

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
     * Update media properties
     */
    public function updateMediaProperties(Gallery $gallery, int $mediaId, array $properties): bool
    {
        return $this->galleryRepository->updateMediaProperties($gallery, $mediaId, $properties);
    }

    /**
     * Reorder galleries
     */
    public function reorderGalleries(array $galleryIds): void
    {
        $this->galleryRepository->reorderGalleries($galleryIds);
    }

    /**
     * Get galleries with pagination
     */
    public function getGalleriesPaginated(Model $model, int $perPage = 10)
    {
        return $this->galleryRepository->getGalleriesPaginated($model, $perPage);
    }

    /**
     * Search galleries
     */
    public function searchGalleries(Model $model, string $search): Collection
    {
        return $this->galleryRepository->searchGalleries($model, $search);
    }

    /**
     * Bulk upload media to gallery
     */
    public function bulkUploadMedia(Gallery $gallery, array $files, array $titles = []): void
    {
        DB::transaction(function () use ($gallery, $files, $titles) {
            foreach ($files as $index => $file) {
                if ($file instanceof UploadedFile) {
                    $customProperties = [
                        'title' => $titles[$index] ?? $file->getClientOriginalName(),
                        'alt_text' => $titles[$index] ?? $file->getClientOriginalName(),
                        'caption' => '',
                    ];

                    $this->galleryRepository->addMediaToGallery($gallery, $file, $customProperties);
                }
            }
        });
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