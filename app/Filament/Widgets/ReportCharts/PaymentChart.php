<?php

namespace App\Filament\Widgets\ReportCharts;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PaymentChart extends ChartWidget
{
    protected ?string $heading = 'Payment Analysis';
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get report data from cache
        $reportKey = session('last_report_key');
        $reportData = cache()->get($reportKey);

        if (!$reportData || !isset($reportData['payments'])) {
            return $this->getEmptyData();
        }

        $payments = $reportData['payments'];
        $statusDistribution = $payments['status_distribution'] ?? [];
        $paymentMethodDistribution = $payments['payment_method_distribution'] ?? [];
        $monthlyRevenue = $payments['monthly_revenue'] ?? [];

        // Prepare status distribution data
        $statusLabels = [];
        $statusData = [];
        $statusColors = ['#10B981', '#F59E0B', '#EF4444', '#6B7280'];

        foreach ($statusDistribution as $status => $stats) {
            $statusLabels[] = ucfirst($status);
            $statusData[] = $stats['count'];
        }

        // Prepare payment method distribution data
        $methodLabels = [];
        $methodData = [];
        $methodColors = ['#8B5CF6', '#06B6D4', '#10B981', '#F59E0B'];

        foreach ($paymentMethodDistribution as $method => $stats) {
            $methodLabels[] = ucfirst($method);
            $methodData[] = $stats['count'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Payment Status Distribution',
                    'data' => $statusData,
                    'backgroundColor' => $statusColors,
                    'borderColor' => $statusColors,
                ],
                [
                    'label' => 'Payment Method Distribution',
                    'data' => $methodData,
                    'backgroundColor' => $methodColors,
                    'borderColor' => $methodColors,
                ],
            ],
            'labels' => $statusLabels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
                            return context.label + ": " + context.parsed + " payments";
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
