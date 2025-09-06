<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListSiteSettings extends ListRecords
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('download_contract')
                ->label('Download Contract Template')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    return $this->downloadContractTemplate();
                })
                ->requiresConfirmation()
                ->modalHeading('Download Contract Template')
                ->modalDescription('This will download the gym partnership agreement template.')
                ->modalSubmitActionLabel('Download'),
        ];
    }

    protected function downloadContractTemplate(): StreamedResponse
    {
        $filePath = 'contracts/Gym_Partnership_Agreement.docx';
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Contract template file not found.');
        }

        $fileName = 'Gym_Partnership_Agreement.docx';
        $fullPath = Storage::disk('public')->path($filePath);

        return response()->streamDownload(function () use ($fullPath) {
            echo file_get_contents($fullPath);
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
