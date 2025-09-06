<?php

namespace App\Services;

use App\Models\{Document, SiteSetting};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContractDocumentService
{
    public function handleContractDocument(SiteSetting $siteSetting): void
    {
        $contractMedia = $siteSetting->getFirstMedia('contract_document');
        
        if (!$contractMedia) {
            Log::info('No contract media found');
            return;
        }

        $document = Document::create(
[
                'title' => $siteSetting->gym_name . ' - Contract Document',
                'description' => 'Contract document for ' . $siteSetting->gym_name,
                'document_type' => 'contract',
                'created_by_id' => Auth::user()?->id ?? 1,
                'is_active' => true,
                'published_at' => now(),
            ]
        );

        if (!$document->getFirstMedia('document')) {
            $contractMedia->copy($document, 'document');
        }

        $document->siteSettings()->sync([$siteSetting->id]);
    }
}
