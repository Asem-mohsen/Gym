<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var User $user */
        $user = Auth::user();

        $data['created_by_id'] = $user->id;
        $data['published_at'] = now();
        
        // If document is internal, ensure no site settings are linked
        if (isset($data['is_internal']) && $data['is_internal']) {
            $data['siteSettings'] = [];
        }
        
        return $data;
    }
}
