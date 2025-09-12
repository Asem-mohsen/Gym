<?php

namespace App\Filament\Widgets\ReportCharts;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MembershipChart extends ChartWidget
{
    protected ?string $heading = 'Membership Overview';
    protected static ?int $sort = 1;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get report data from cache
        $reportKey = session('last_report_key');
        $reportData = cache()->get($reportKey);

        if (!$reportData || !isset($reportData['memberships'])) {
            return $this->getEmptyData();
        }

        $memberships = $reportData['memberships'];
        $monthlyTrend = $memberships['monthly_trend'] ?? [];
        $periodDistribution = $memberships['period_distribution'] ?? [];

        // Prepare monthly trend data
        $labels = [];
        $data = [];
        foreach ($monthlyTrend as $month => $stats) {
            $labels[] = Carbon::createFromFormat('Y-m', $month)->format('M Y');
            $data[] = $stats['count'];
        }

        // Prepare period distribution data
        $periodLabels = [];
        $periodData = [];
        $colors = ['#8B5CF6', '#06B6D4', '#10B981', '#F59E0B', '#EF4444'];

        foreach ($periodDistribution as $period => $stats) {
            $periodLabels[] = ucfirst($period);
            $periodData[] = $stats['count'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Memberships',
                    'data' => $data,
                    'backgroundColor' => '#8B5CF6',
                    'borderColor' => '#7C3AED',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
