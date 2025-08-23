<?php

namespace App\Repositories;

use App\Models\Document;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentRepository
{
    protected $model;

    public function __construct(Document $model)
    {
        $this->model = $model;
    }

    public function getDocumentsForGym(int $siteSettingId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->where('is_active', true)
            ->where(function($q) use ($siteSettingId) {
                $q->whereHas('siteSettings', function($subQ) use ($siteSettingId) {
                    $subQ->where('site_settings.id', $siteSettingId);
                })->orWhereDoesntHave('siteSettings');
            });

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('media', function($mediaQ) use ($search) {
                      $mediaQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['type'])) {
            $query->where('document_type', $filters['type']);
        }

        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query->orderBy('title', 'asc');
                break;
            case 'type':
                $query->orderBy('document_type', 'asc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate(12);
    }

    public function isDocumentAvailableForGym(Document $document, int $siteSettingId): bool
    {
        return $document->siteSettings()->where('site_settings.id', $siteSettingId)->exists() || $document->siteSettings()->count() === 0;
    }

    public function getTotalDocumentsForGym(int $siteSettingId): int
    {
        return $this->model->where('is_active', true)
            ->where(function($q) use ($siteSettingId) {
                $q->whereHas('siteSettings', function($subQ) use ($siteSettingId) {
                    $subQ->where('site_settings.id', $siteSettingId);
                })->orWhereDoesntHave('siteSettings');
            })->count();
    }

}
