<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Services\ContractDocumentService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $siteSetting = $this->record;
        
        $this->handleOwnerAssignment($siteSetting);
        
        $service = new ContractDocumentService();
        $service->handleContractDocument($siteSetting);
    }
    
    private function handleOwnerAssignment($siteSetting): void
    {
        if ($siteSetting->owner_id) {
            $existingAssignment = $siteSetting->users()
                ->where('user_id', $siteSetting->owner_id)
                ->exists();
            
            if (!$existingAssignment) {
                $siteSetting->users()->attach($siteSetting->owner_id);
            }
        }
    }
}
