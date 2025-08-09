<?php

namespace App\Filament\Widgets;

use App\Models\SiteSetting;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Gyms', SiteSetting::count())
                ->description('All gyms in the system')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Total Users', User::whereHas('role', function ($query) {
                    $query->where('name', 'user');
                })->count())
                ->description('Users with role "user"')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Total Staff', User::whereHas('role', function ($query) {
                    $query->whereNotIn('name', ['user']);
                })->count())
                ->description('All staff members (admin, coach, staff, trainer)')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
