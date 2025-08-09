<?php

namespace App\Filament\Widgets;

use App\Models\SiteSetting;
use App\Models\Subscription;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class SubscribersPerGymChart extends ChartWidget
{
    protected static ?string $heading = 'Subscribers Per Gym & Membership';
    protected static ?int $sort = 5;

    public ?string $selectedGym = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
        $query = SiteSetting::query();
        
        if ($this->selectedGym) {
            $query->where('id', $this->selectedGym);
        }

        $gyms = $query->with(['memberships'])->get();
        
        $datasets = [];
        $labels = [];
        
        foreach ($gyms as $gym) {
            foreach ($gym->memberships as $membership) {
                // Count subscriptions manually with date filtering
                $subscriptionQuery = Subscription::where('membership_id', $membership->id);
                
                if ($this->dateFrom) {
                    $subscriptionQuery->where('created_at', '>=', Carbon::parse($this->dateFrom));
                }
                if ($this->dateTo) {
                    $subscriptionQuery->where('created_at', '<=', Carbon::parse($this->dateTo));
                }
                
                $subscriptionCount = $subscriptionQuery->count();
                
                $labels[] = $gym->gym_name . ' - ' . $membership->name;
                $datasets[] = $subscriptionCount;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Subscribers',
                    'data' => $datasets,
                    'backgroundColor' => '#F59E0B',
                    'borderColor' => '#D97706',
                ],
            ],
            'labels' => $labels,
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
