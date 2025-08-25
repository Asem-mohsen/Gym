<?php

namespace App\Filament\Widgets\ReportCharts;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class UserActivityChart extends ChartWidget
{
    protected static ?string $heading = 'User Activity';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get report data from cache
        $reportKey = session('last_report_key');
        $reportData = cache()->get($reportKey);

        if (!$reportData || !isset($reportData['users'])) {
            return $this->getEmptyData();
        }

        $users = $reportData['users'];
        $genderDistribution = $users['gender_distribution'] ?? [];
        $monthlyRegistrations = $users['monthly_registrations'] ?? [];

        // Prepare gender distribution data
        $genderLabels = [];
        $genderData = [];
        $genderColors = ['#8B5CF6', '#06B6D4'];

        foreach ($genderDistribution as $gender => $stats) {
            $genderLabels[] = ucfirst($gender);
            $genderData[] = $stats['count'];
        }

        // Prepare monthly registrations data
        $monthLabels = [];
        $monthData = [];
        foreach ($monthlyRegistrations as $month => $count) {
            $monthLabels[] = Carbon::createFromFormat('Y-m', $month)->format('M Y');
            $monthData[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Registrations',
                    'data' => $monthData,
                    'backgroundColor' => '#10B981',
                    'borderColor' => '#059669',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $monthLabels,
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
