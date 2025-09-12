<?php

namespace App\Filament\Widgets\ReportCharts;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TrainerInsightsChart extends ChartWidget
{
    protected ?string $heading = 'Trainer Insights';
    protected static ?int $sort = 6;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get report data from cache
        $reportKey = session('last_report_key');
        $reportData = cache()->get($reportKey);

        if (!$reportData || !isset($reportData['trainer_insights'])) {
            return $this->getEmptyData();
        }

        $trainerInsights = $reportData['trainer_insights'];
        $topTrainers = $trainerInsights['top_trainers'] ?? [];

        // Prepare top trainers data
        $labels = [];
        $data = [];
        foreach ($topTrainers as $trainer) {
            $labels[] = $trainer['name'];
            $data[] = $trainer['sessions_count'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sessions Conducted',
                    'data' => $data,
                    'backgroundColor' => '#8B5CF6',
                    'borderColor' => '#7C3AED',
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
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => 'function(context) {
                            return "Sessions: " + context.parsed;
                        }',
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    protected function getEmptyData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'No Data Available',
                    'data' => [0],
                    'backgroundColor' => '#9CA3AF',
                    'borderColor' => '#6B7280',
                ],
            ],
            'labels' => ['No Data'],
        ];
    }
}
