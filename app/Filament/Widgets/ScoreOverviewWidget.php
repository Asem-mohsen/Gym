<?php

namespace App\Filament\Widgets;

use App\Models\BranchScore;
use App\Models\BranchScoreReviewRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ScoreOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBranches = BranchScore::count();
        $averageScore = BranchScore::avg('score') ?? 0;
        $pendingReviews = BranchScoreReviewRequest::where('is_reviewed', false)->count();
        $excellentScores = BranchScore::where('score', '>=', 90)->count();
        $goodScores = BranchScore::whereBetween('score', [70, 89])->count();
        $poorScores = BranchScore::where('score', '<', 60)->count();

        return [
            Stat::make('Total Branches', $totalBranches)
                ->description('Branches with scores')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),

            Stat::make('Average Score', number_format($averageScore, 1))
                ->description('Across all branches')
                ->descriptionIcon('heroicon-m-star')
                ->color($averageScore >= 80 ? 'success' : ($averageScore >= 60 ? 'warning' : 'danger')),

            Stat::make('Pending Reviews', $pendingReviews)
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingReviews > 0 ? 'warning' : 'success'),

            Stat::make('Excellent (90+)', $excellentScores)
                ->description('Top performing branches')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),

            Stat::make('Good (70-89)', $goodScores)
                ->description('Well performing branches')
                ->descriptionIcon('heroicon-m-thumb-up')
                ->color('info'),

            Stat::make('Poor (<60)', $poorScores)
                ->description('Need improvement')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
