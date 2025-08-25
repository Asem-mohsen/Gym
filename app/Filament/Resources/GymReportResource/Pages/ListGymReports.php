<?php

namespace App\Filament\Resources\GymReportResource\Pages;

use App\Filament\Resources\GymReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGymReports extends ListRecords
{
    protected static string $resource = GymReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Generate New Report')
                ->icon('heroicon-o-plus'),
        ];
    }
}
