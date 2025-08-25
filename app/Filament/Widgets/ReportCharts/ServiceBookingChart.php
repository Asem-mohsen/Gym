<?php

namespace App\Filament\Widgets\ReportCharts;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ServiceBookingChart extends ChartWidget
{
    protected static ?string $heading = 'Service & Class Bookings';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Get report data from cache
        $reportKey = session('last_report_key');
        $reportData = cache()->get($reportKey);

        if (!$reportData || !isset($reportData['classes_services'])) {
            return $this->getEmptyData();
        }

        $classesServices = $reportData['classes_services'];
        $mostBookedServices = $classesServices['most_booked_services'] ?? [];
        $mostBookedClasses = $classesServices['most_booked_classes'] ?? [];

        // Prepare most booked services data
        $serviceLabels = [];
        $serviceData = [];
        foreach ($mostBookedServices as $service) {
            $serviceLabels[] = $service->name ?? 'Unknown Service';
            $serviceData[] = $service->bookings->count();
        }

        // Prepare most booked classes data
        $classLabels = [];
        $classData = [];
        foreach ($mostBookedClasses as $class) {
            $classLabels[] = $class->name ?? 'Unknown Class';
            $classData[] = $class->bookings->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Most Booked Services',
                    'data' => $serviceData,
                    'backgroundColor' => '#8B5CF6',
                    'borderColor' => '#7C3AED',
                ],
                [
                    'label' => 'Most Booked Classes',
                    'data' => $classData,
                    'backgroundColor' => '#06B6D4',
                    'borderColor' => '#0891B2',
                ],
            ],
            'labels' => $serviceLabels,
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
