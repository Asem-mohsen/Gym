<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Services\ContractDocumentService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteSetting extends CreateRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function afterCreate(): void
    {
        $siteSetting = $this->record;
        
        $service = new ContractDocumentService();
        $service->handleContractDocument($siteSetting);
    }
}
