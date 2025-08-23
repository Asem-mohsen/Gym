<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var User $user */
        $user = Auth::user();

        $data['updated_by_id'] = $user->id;
        
        // If document is internal, ensure no site settings are linked
        if (isset($data['is_internal']) && $data['is_internal']) {
            $data['siteSettings'] = [];
        }
        
        return $data;
    }
}
