<?php

namespace App\Filament\Resources\BranchScoreResource\Pages;

use App\Filament\Resources\BranchScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBranchScores extends ListRecords
{
    protected static string $resource = BranchScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
