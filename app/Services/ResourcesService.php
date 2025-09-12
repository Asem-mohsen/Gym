<?php

namespace App\Services;

use Exception;
use App\Repositories\DocumentRepository;
use App\Models\{Document, SiteSetting};
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ResourcesService
{
    public function __construct(
        protected DocumentRepository $documentRepository,
        protected SiteSettingService $siteSettingService,
        protected NotificationService $notificationService
    ) {
        $this->documentRepository = $documentRepository;
        $this->siteSettingService = $siteSettingService;
        $this->notificationService = $notificationService;
    }

    public function getDocumentsForGym(Request $request, int $siteSettingId): array
    {
        $filters = [
            'search' => $request->get('search'),
            'type' => $request->get('type'),
            'sort' => $request->get('sort', 'newest'),
        ];

        $documents = $this->documentRepository->getDocumentsForGym($siteSettingId, $filters);
        
        return [
            'documents' => $documents,
        ];
    }

    public function downloadDocument(Document $document, int $siteSettingId): BinaryFileResponse
    {
        if (!$this->documentRepository->isDocumentAvailableForGym($document, $siteSettingId)) {
            abort(403, 'Document not available for this gym.');
        }

        $media = $document->getFirstMedia('document');
        
        if (!$media) {
            abort(404, 'Document file not found.');
        }

        // Here you could log the download for analytics
        // $this->logDownload($document, $siteSettingId);

        return response()->download($media->getPath(), $media->name);
    }

    /**
     * Handle new resource assignment and send notifications
     */
    public function handleNewResourceAssignment(Document $document, int $siteSettingId, $assignedBy = null): void
    {
        try {
            $siteSetting = SiteSetting::find($siteSettingId);
            if ($siteSetting) {
                $this->notificationService->sendNewResourceAssignmentNotification($document, $siteSetting, $assignedBy);
            }
        } catch (Exception $e) {
            // Log error but don't fail the main operation
            Log::error('Failed to send resource assignment notification', [
                'document_id' => $document->id,
                'site_setting_id' => $siteSettingId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
