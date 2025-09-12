<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BranchesPerGymChart;
use App\Filament\Widgets\ClassesPerGymChart;
use App\Filament\Widgets\MembershipsPerGymChart;
use App\Filament\Widgets\StaffPerGymChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SubscribersPerGymChart;
use App\Filament\Widgets\UsersPerGymChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            BranchesPerGymChart::class,
            UsersPerGymChart::class,
            MembershipsPerGymChart::class,
            SubscribersPerGymChart::class,
            StaffPerGymChart::class,
            ClassesPerGymChart::class,
        ];
    }

    public function getWidgets(): array
    {
        return [];
    }
}
