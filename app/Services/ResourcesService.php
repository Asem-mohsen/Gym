<?php

namespace App\Services;

use App\Repositories\DocumentRepository;
use App\Models\Document;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ResourcesService
{
    public function __construct(
        protected DocumentRepository $documentRepository,
        protected SiteSettingService $siteSettingService
    ) {
        $this->documentRepository = $documentRepository;
        $this->siteSettingService = $siteSettingService;
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



}
