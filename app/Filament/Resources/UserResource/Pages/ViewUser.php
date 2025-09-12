<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Grid;
use Filament\Support\Enums\FontWeight;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('User Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Name')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('email')
                                    ->label('Email')
                                    ->copyable(),
                                TextEntry::make('phone')
                                    ->label('Phone')
                                    ->copyable(),
                                TextEntry::make('gender')
                                    ->label('Gender')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'male' => 'info',
                                        'female' => 'success',
                                        default => 'gray',
                                    }),
                                TextEntry::make('address')
                                    ->label('Address')
                                    ->columnSpanFull(),
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime(),
                                TextEntry::make('last_visit_at')
                                    ->label('Last Visit')
                                    ->dateTime()
                                    ->placeholder('Never'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Gym Ownership')
                    ->schema([
                        TextEntry::make('site.gym_name')
                            ->label('Gym Name')
                            ->weight(FontWeight::Bold)
                            ->placeholder('No gym owned')
                            ->visible(fn ($record) => $record->site),
                        TextEntry::make('site.address')
                            ->label('Gym Address')
                            ->placeholder('No address set')
                            ->visible(fn ($record) => $record->site),
                        TextEntry::make('site.description')
                            ->label('Gym Description')
                            ->placeholder('No description set')
                            ->visible(fn ($record) => $record->site),
                        TextEntry::make('site.phone')
                            ->label('Gym Phone')
                            ->placeholder('No phone set')
                            ->visible(fn ($record) => $record->site),
                        TextEntry::make('site.email')
                            ->label('Gym Email')
                            ->placeholder('No email set')
                            ->visible(fn ($record) => $record->site),
                        TextEntry::make('site.website')
                            ->label('Gym Website')
                            ->placeholder('No website set')
                            ->visible(fn ($record) => $record->site),
                        TextEntry::make('site.created_at')
                            ->label('Gym Created At')
                            ->dateTime()
                            ->visible(fn ($record) => $record->site),
                    ])
                    ->visible(fn ($record) => $record->site)
                    ->collapsible(),

                Section::make('Branches')
                    ->schema([
                        RepeatableEntry::make('site.branches')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Branch Name')
                                            ->weight(FontWeight::Bold),
                                        TextEntry::make('location')
                                            ->label('Location'),
                                        TextEntry::make('type')
                                            ->label('Type')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'mix' => 'info',
                                                'women' => 'success',
                                                'men' => 'primary',
                                                default => 'gray',
                                            }),
                                        TextEntry::make('size')
                                            ->label('Size (sq ft)'),
                                        TextEntry::make('manager.name')
                                            ->label('Manager')
                                            ->placeholder('No manager assigned'),
                                        TextEntry::make('created_at')
                                            ->label('Created At')
                                            ->dateTime(),
                                    ]),
                            ])
                            ->placeholder('No branches found')
                            ->visible(fn ($record) => $record->site && $record->site->branches->count() > 0),
                    ])
                    ->visible(fn ($record) => $record->site)
                    ->collapsible(),

                Section::make('Memberships')
                    ->schema([
                        RepeatableEntry::make('site.memberships')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Membership Name')
                                            ->weight(FontWeight::Bold),
                                        TextEntry::make('price')
                                            ->label('Price')
                                            ->money('USD'),
                                        TextEntry::make('period')
                                            ->label('Period'),
                                        TextEntry::make('billing_interval')
                                            ->label('Billing Interval')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'monthly' => 'info',
                                                'yearly' => 'success',
                                                default => 'gray',
                                            }),
                                        TextEntry::make('invitation_limit')
                                            ->label('Invitation Limit'),
                                        IconEntry::make('status')
                                            ->label('Status')
                                            ->boolean()
                                            ->trueIcon('heroicon-o-check-circle')
                                            ->falseIcon('heroicon-o-x-circle')
                                            ->trueColor('success')
                                            ->falseColor('danger'),
                                    ]),
                            ])
                            ->placeholder('No memberships found')
                            ->visible(fn ($record) => $record->site && $record->site->memberships->count() > 0),
                    ])
                    ->visible(fn ($record) => $record->site)
                    ->collapsible(),

                Section::make('Services')
                    ->schema([
                        RepeatableEntry::make('site.services')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Service Name')
                                            ->weight(FontWeight::Bold),
                                        TextEntry::make('price')
                                            ->label('Price')
                                            ->money('USD'),
                                        TextEntry::make('duration')
                                            ->label('Duration (minutes)'),
                                        TextEntry::make('booking_type')
                                            ->label('Booking Type')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'free_booking' => 'success',
                                                'paid_booking' => 'warning',
                                                'unbookable' => 'danger',
                                                default => 'gray',
                                            }),
                                        IconEntry::make('requires_payment')
                                            ->label('Requires Payment')
                                            ->boolean()
                                            ->trueIcon('heroicon-o-check-circle')
                                            ->falseIcon('heroicon-o-x-circle')
                                            ->trueColor('warning')
                                            ->falseColor('success'),
                                        IconEntry::make('is_available')
                                            ->label('Available')
                                            ->boolean()
                                            ->trueIcon('heroicon-o-check-circle')
                                            ->falseIcon('heroicon-o-x-circle')
                                            ->trueColor('success')
                                            ->falseColor('danger'),
                                    ]),
                            ])
                            ->placeholder('No services found')
                            ->visible(fn ($record) => $record->site && $record->site->services->count() > 0),
                    ])
                    ->visible(fn ($record) => $record->site)
                    ->collapsible(),

                Section::make('Statistics')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('site.branches_count')
                                    ->label('Total Branches')
                                    ->state(fn ($record) => $record->site?->branches->count() ?? 0)
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('site.memberships_count')
                                    ->label('Total Memberships')
                                    ->state(fn ($record) => $record->site?->memberships->count() ?? 0)
                                    ->badge()
                                    ->color('success'),
                                TextEntry::make('site.services_count')
                                    ->label('Total Services')
                                    ->state(fn ($record) => $record->site?->services->count() ?? 0)
                                    ->badge()
                                    ->color('warning'),
                                TextEntry::make('site.users_count')
                                    ->label('Total Users')
                                    ->state(fn ($record) => $record->site?->users->count() ?? 0)
                                    ->badge()
                                    ->color('primary'),
                            ])
                            ->visible(fn ($record) => $record->site),
                    ])
                    ->visible(fn ($record) => $record->site)
                    ->collapsible(),
            ]);
    }
}
