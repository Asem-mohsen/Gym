<?php

namespace App\Filament\Resources\GymReportResource\Pages;

use App\Filament\Resources\GymReportResource;
use App\Models\GymReport;
use App\Services\Reports\ReportService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CreateGymReport extends CreateRecord
{
    protected static string $resource = GymReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $reportService = new ReportService();
        $report = $reportService->generateReport($data);

        /** @var User $user */
        $user = auth()->user();
        if ($report) {
            $gymReport = GymReport::create([
                'gym_id' => $data['gym_id'],
                'report_sections' => $data['report_sections'],
                'date_from' => $data['date_from'],
                'date_to' => $data['date_to'],
                'export_format' => $data['export_format'],
                'status' => 'Document Created',
                'generated_by' => $user->id,
            ]);

            Notification::make()
                ->title('Report generated successfully')
                ->body('The report has been saved as a document and is available in the Documents section.')
                ->success()
                ->send();

            return $gymReport;
        } else {
            Notification::make()
                ->title('Report generation failed')
                ->body('There was an error generating the report. Please try again.')
                ->danger()
                ->send();

            return new class extends Model {
                public $id = 1;
                public $gym_name = 'Report Failed';
                public $report_sections = [];
                public $date_from = null;
                public $date_to = null;
                public $export_format = 'pdf';
                public $created_at = null;
                public $status = 'Failed';
            };
        }
    }
}
