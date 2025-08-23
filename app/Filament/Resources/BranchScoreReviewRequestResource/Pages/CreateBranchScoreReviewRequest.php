<?php

namespace App\Filament\Resources\BranchScoreReviewRequestResource\Pages;

use App\Filament\Resources\BranchScoreReviewRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;

class CreateBranchScoreReviewRequest extends CreateRecord
{
    protected static string $resource = BranchScoreReviewRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
