<?php

namespace App\Filament\Widgets;

use App\Models\BranchScore;
use Filament\Widgets\ChartWidget;

class ScoreDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Score Distribution';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $excellent = BranchScore::where('score', '>=', 90)->count();
        $veryGood = BranchScore::whereBetween('score', [80, 89])->count();
        $good = BranchScore::whereBetween('score', [70, 79])->count();
        $average = BranchScore::whereBetween('score', [60, 69])->count();
        $poor = BranchScore::where('score', '<', 60)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Number of Branches',
                    'data' => [$excellent, $veryGood, $good, $average, $poor],
                    'backgroundColor' => [
                        '#10b981', // success - excellent
                        '#3b82f6', // info - very good
                        '#6366f1', // primary - good
                        '#f59e0b', // warning - average
                        '#ef4444', // danger - poor
                    ],
                ],
            ],
            'labels' => ['Excellent (90+)', 'Very Good (80-89)', 'Good (70-79)', 'Average (60-69)', 'Poor (<60)'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
