<?php

namespace App\Services;

use App\Models\{Document, SiteSetting};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContractDocumentService
{
    public function handleContractDocument(SiteSetting $siteSetting): void
    {
        Log::info('Processing contract document for site setting: ' . $siteSetting->id);
        
        // Check all media for this site setting
        $allMedia = $siteSetting->getMedia();
        Log::info('All media for site setting ' . $siteSetting->id . ': ' . $allMedia->count() . ' items');
        
        foreach ($allMedia as $media) {
            Log::info('Media item: ' . $media->collection_name . ' - ' . $media->name);
        }
        
        $contractMedia = $siteSetting->getFirstMedia('contract_document');
        
        if (!$contractMedia) {
            Log::info('No contract media found for site setting: ' . $siteSetting->id);
            return;
        }
        
        Log::info('Contract media found: ' . $contractMedia->name);

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

        Log::info('Contract media found');
        if (!$document->getFirstMedia('document')) {
            $contractMedia->copy($document, 'document');
        }

        $document->siteSettings()->sync([$siteSetting->id]);
        Log::info('Contract media synced');
    }
}
