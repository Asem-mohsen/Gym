<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Create Admin';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = null;
        $data['is_admin'] = true;
        
        return $data;
    }
}
