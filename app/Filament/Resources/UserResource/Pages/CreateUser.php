<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Services\OnBoarding\AdminOnboardingService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static ?string $title = 'Create Admin';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set a temporary password that will be changed via onboarding
        $data['password'] = bcrypt(Str::random(32));
        $data['is_admin'] = true;
        $data['role_id'] = 1; // Admin role
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;
        
        $service = new AdminOnboardingService();
        $success = $service->sendOnboardingEmail($user);
        
        if ($success) {
            $this->notify('success', 'Admin created successfully. Onboarding email has been sent.');
        } else {
            $this->notify('warning', 'Admin created successfully, but failed to send onboarding email.');
        }
    }
}
