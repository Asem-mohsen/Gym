<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\GymReportResource\Pages\ListGymReports;
use App\Filament\Resources\GymReportResource\Pages\CreateGymReport;
use App\Filament\Resources\GymReportResource\Pages;
use App\Models\GymReport;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class GymReportResource extends Resource
{
    protected static ?string $model = GymReport::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';
    protected static string | \UnitEnum | null $navigationGroup = 'Analytics';
    protected static ?string $navigationLabel = 'Gym Reports';
    protected static ?string $modelLabel = 'Gym Report';
    protected static ?string $pluralModelLabel = 'Gym Reports';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Report Configuration')
                    ->description('Select the gym and data types to include in your report')
                    ->schema([
                        Select::make('gym_id')
                            ->label('Select Gym')
                            ->options(SiteSetting::pluck('gym_name', 'id'))
                            ->required()
                            ->searchable()
                            ->placeholder('Choose a gym'),
                        
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('date_from')
                                    ->label('Start Date')
                                    ->default(now()->subMonth())
                                    ->required(),
                                
                                DatePicker::make('date_to')
                                    ->label('End Date')
                                    ->default(now())
                                    ->required(),
                            ]),
                    ])
                    ->collapsible(false),

                Section::make('Data Selection')
                    ->description('Choose which data types to include in your report')
                    ->schema([
                        CheckboxList::make('report_sections')
                            ->label('Report Sections')
                            ->options([
                                'memberships' => 'Memberships',
                                'subscriptions' => 'Subscriptions',
                                'users' => 'Users & Visitors',
                                'payments' => 'Payment Analysis',
                                'invitations' => 'Invitations',
                                'score_analysis' => 'Score Analysis',
                                'classes_services' => 'Classes & Services',
                                'trainer_insights' => 'Trainer Insights',
                                'page_views' => 'Most Viewed Pages',
                            ])
                            ->columns(3)
                            ->default([
                                'memberships',
                                'payments',
                                'classes_services'
                            ])
                            ->required(),
                    ])
                    ->collapsible(false),

                Section::make('Export Options')
                    ->description('Choose your preferred export format')
                    ->schema([
                        Select::make('export_format')
                            ->label('Export Format')
                            ->options([
                                'pdf' => 'PDF Report',
                                'excel' => 'Excel Spreadsheet',
                                'both' => 'Both PDF & Excel',
                            ])
                            ->default('pdf')
                            ->required(),
                    ])
                    ->collapsible(false),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gym.gym_name')
                    ->label('Gym Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('report_sections')
                    ->label('Report Sections')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return implode(', ', array_map('ucfirst', $state));
                        }
                        return $state;
                    })
                    ->wrap(),
                TextColumn::make('date_from')
                    ->label('From Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_to')
                    ->label('To Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('export_format')
                    ->label('Format')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pdf' => 'success',
                        'excel' => 'info',
                        'both' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Generated At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('success'),
            ])
            ->recordActions([
                Action::make('view_documents')
                    ->label('View Documents')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (GymReport $record) => route('filament.admin.resources.documents.index', ['document_type' => 'report']))
                    ->color('primary')
                    ->openUrlInNewTab()
                    ->visible(fn (GymReport $record) => $record->status === 'Document Created'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGymReports::route('/'),
            'create' => CreateGymReport::route('/create'),
        ];
    }
}
