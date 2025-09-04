<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserPhotoService
{
    /**
     * Handle photo uploads for a user
     */
    public function handlePhotoUploads(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data) {

            if (isset($data['delete_photos']) && is_array($data['delete_photos'])) {
                $this->deletePhotos($user, $data['delete_photos']);
            }

            if (isset($data['photos']) && is_array($data['photos'])) {
                $this->uploadPhotos($user, $data['photos'], $data['new_photo_titles'] ?? []);
            }

            if (isset($data['photo_titles']) && is_array($data['photo_titles'])) {
                $this->updatePhotoTitles($data['photo_titles']);
            }
        });
    }

    /**
     * Upload new photos for a user
     */
    protected function uploadPhotos(User $user, array $photos, array $titles = []): void
    {
        foreach ($photos as $index => $photo) {
            if ($photo instanceof UploadedFile) {
                $userPhoto = UserPhoto::create([
                    'user_id' => $user->id,
                    'title' => $titles[$index] ?? null,
                    'is_public' => true,
                    'sort_order' => $user->photos()->count(),
                ]);

                $userPhoto->addMediaFromRequest("photos.{$index}")
                    ->toMediaCollection('user_photos');
            }
        }
    }

    /**
     * Delete photos for a user
     */
    protected function deletePhotos(User $user, array $photoIds): void
    {
        $photos = $user->photos()->whereIn('id', $photoIds)->get();
        
        foreach ($photos as $photo) {
            $photo->clearMediaCollection('user_photos');
            $photo->delete();
        }
    }

    /**
     * Update photo titles
     */
    protected function updatePhotoTitles(array $titles): void
    {
        foreach ($titles as $photoId => $title) {
            UserPhoto::where('id', $photoId)->update(['title' => $title]);
        }
    }

    /**
     * Get user photos with pagination
     */
    public function getUserPhotos(User $user, bool $publicOnly = false, int $perPage = 12)
    {
        $query = $user->photos()->orderBy('sort_order')->orderBy('created_at', 'desc');
        
        if ($publicOnly) {
            $query->public();
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Get user photo statistics
     */
    public function getUserPhotoStats(User $user): array
    {
        return [
            'total_photos' => $user->photos()->count(),
            'public_photos' => $user->photos()->public()->count(),
            'private_photos' => $user->photos()->where('is_public', false)->count(),
        ];
    }
}
