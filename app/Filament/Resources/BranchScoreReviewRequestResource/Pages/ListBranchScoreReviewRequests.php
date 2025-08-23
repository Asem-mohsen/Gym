<?php

namespace App\Filament\Resources\BranchScoreReviewRequestResource\Pages;

use App\Filament\Resources\BranchScoreReviewRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBranchScoreReviewRequests extends ListRecords
{
    protected static string $resource = BranchScoreReviewRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
