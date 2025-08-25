<?php

namespace App\Filament\Widgets\ReportCharts;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ScoreAnalysisChart extends ChartWidget
{
    protected static ?string $heading = 'Score Analysis';
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get report data from cache
        $reportKey = session('last_report_key');
        $reportData = cache()->get($reportKey);

        if (!$reportData || !isset($reportData['score_analysis'])) {
            return $this->getEmptyData();
        }

        $scoreAnalysis = $reportData['score_analysis'];
        $scoreDistribution = $scoreAnalysis['score_distribution'] ?? [];

        // Prepare score distribution data
        $labels = [];
        $data = [];
        $colors = ['#10B981', '#F59E0B', '#EF4444', '#6B7280'];

        foreach ($scoreDistribution as $range => $count) {
            $labels[] = ucfirst($range);
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Score Distribution',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.label + ": " + context.parsed + " scores";
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
                    'data' => [1],
                    'backgroundColor' => ['#9CA3AF'],
                    'borderColor' => ['#6B7280'],
                ],
            ],
            'labels' => ['No Data'],
        ];
    }
}
