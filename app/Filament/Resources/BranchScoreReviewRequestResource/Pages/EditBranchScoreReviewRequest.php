<?php

namespace App\Filament\Resources\BranchScoreReviewRequestResource\Pages;

use App\Filament\Resources\BranchScoreReviewRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;

class EditBranchScoreReviewRequest extends EditRecord
{
    protected static string $resource = BranchScoreReviewRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var User $user */
        $user = auth()->user();

        $data['reviewed_by_id'] = $user->id;
        $data['reviewed_at'] = now();
        $data['is_reviewed'] = true;
        $data['is_approved'] = true;
        return $data;
    }
}
