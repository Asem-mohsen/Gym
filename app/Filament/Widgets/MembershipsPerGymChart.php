<?php

namespace App\Filament\Widgets;

use Filament\Schemas\Schema;
use App\Models\SiteSetting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class MembershipsPerGymChart extends ChartWidget
{
    protected ?string $heading = 'Memberships Per Gym';
    protected static ?int $sort = 4;

    public ?array $data = [];
    public ?string $selectedGym = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('selectedGym')
                    ->label('Filter by Gym')
                    ->options(SiteSetting::pluck('gym_name', 'id'))
                    ->placeholder('All Gyms')
                    ->searchable()
                    ->reactive(),
                DatePicker::make('dateFrom')
                    ->label('From Date')
                    ->reactive(),
                DatePicker::make('dateTo')
                    ->label('To Date')
                    ->reactive(),
            ]);
    }

    protected function getData(): array
    {
        $query = SiteSetting::withCount(['memberships' => function (Builder $query) {
            if ($this->dateFrom) {
                $query->where('created_at', '>=', Carbon::parse($this->dateFrom));
            }
            if ($this->dateTo) {
                $query->where('created_at', '<=', Carbon::parse($this->dateTo));
            }
        }]);

        if ($this->selectedGym) {
            $query->where('id', $this->selectedGym);
        }

        $gyms = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Number of Memberships',
                    'data' => $gyms->pluck('memberships_count')->toArray(),
                    'backgroundColor' => '#8B5CF6',
                    'borderColor' => '#7C3AED',
                ],
            ],
            'labels' => $gyms->pluck('gym_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
