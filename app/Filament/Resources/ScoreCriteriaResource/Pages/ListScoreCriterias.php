<?php

namespace App\Filament\Resources\ScoreCriteriaResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ScoreCriteriaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScoreCriterias extends ListRecords
{
    protected static string $resource = ScoreCriteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
