<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Models\User;
use App\Services\ContractDocumentService;
use App\Services\RoleAssignmentService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateSiteSetting extends CreateRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $gymName = is_array($data['gym_name']) ? $data['gym_name']['en'] ?? '' : $data['gym_name'];
        $data['slug'] = Str::slug($gymName);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $siteSetting = $this->record;
        
        $this->handleOwnerAssignment($siteSetting);
        
        $service = new ContractDocumentService();
        $service->handleContractDocument($siteSetting);
    }
    
    private function handleOwnerAssignment($siteSetting): void
    {
        if ($siteSetting->owner_id) {
            $owner = User::find($siteSetting->owner_id);
            
            if ($owner) {
                // Check if user is already assigned to this gym
                $existingAssignment = $siteSetting->users()
                    ->where('user_id', $siteSetting->owner_id)
                    ->exists();
                
                if (!$existingAssignment) {
                    $siteSetting->users()->attach($siteSetting->owner_id);
                }
                
                $roleService = new RoleAssignmentService();
                if (!$owner->hasRole('admin')) {
                    $roleService->assignAdminRole($owner);
                }
            }
        }
    }
}
