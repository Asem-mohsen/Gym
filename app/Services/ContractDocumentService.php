<?php

namespace App\Services;

use App\Models\Document;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractDocumentService
{
    public function handleContractDocument(SiteSetting $siteSetting): void
    {
        $contractMedia = $siteSetting->getFirstMedia('contract_document');
        
        if (!$contractMedia) {
            return;
        }

        $document = Document::updateOrCreate(
            [
                'document_type' => 'contract',
                'created_by_id' => Auth::user()?->id ?? 1,
            ],
            [
                'title' => $siteSetting->gym_name . ' - Contract Document',
                'description' => 'Contract document for ' . $siteSetting->gym_name,
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
