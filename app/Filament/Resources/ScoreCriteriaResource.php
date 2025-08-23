<?php

namespace App\Filament\Resources;

use App\Exports\ScoreCriteriaExport;
use App\Filament\Resources\ScoreCriteriaResource\Pages;
use App\Models\ScoreCriteria;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\Exports\ExportBulkAction;
use Filament\Tables\Actions\Action;

class ScoreCriteriaResource extends Resource
{
    protected static ?string $model = ScoreCriteria::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Score Management';

    protected static ?string $navigationLabel = 'Score Criteria';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name.en')
                            ->label('Name (English)')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name.ar')
                            ->label('Name (Arabic)')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description.en')
                            ->label('Description (English)')
                            ->rows(3)
                            ->maxLength(1000),
                        Textarea::make('description.ar')
                            ->label('Description (Arabic)')
                            ->rows(3)
                            ->maxLength(1000),
                    ])->columns(2),

                Section::make('Scoring Settings')
                    ->schema([
                        TextInput::make('points')
                            ->label('Points')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->helperText('Enter positive points for achievements, negative for penalties'),
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        Toggle::make('is_negative')
                            ->label('Is Negative Point')
                            ->helperText('Check if this is a penalty/deduction item')
                            ->inline(false)
                            ->default(false),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->inline(false)
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('points')
                    ->label('Points')
                    ->sortable()
                    ->badge()
                    ->color(fn (ScoreCriteria $record): string => $record->is_negative ? 'danger' : 'success'),
                TextColumn::make('type')
                    ->label('Type')
                    ->getStateUsing(fn (ScoreCriteria $record): string => $record->is_negative ? 'Penalty' : 'Achievement')
                    ->badge()
                    ->color(fn (ScoreCriteria $record): string => $record->is_negative ? 'danger' : 'success'),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('is_negative')
                    ->label('Point Type'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Export Score Criteria')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        Select::make('format')
                            ->label('Export Format')
                            ->options([
                                'xlsx' => 'Excel (.xlsx)',
                                'csv' => 'CSV (.csv)',
                                'pdf' => 'PDF (.pdf)',
                            ])
                            ->default('xlsx')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return ScoreCriteriaExport::download($data['format']);
                    }),
            ])
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListScoreCriterias::route('/'),
            'create' => Pages\CreateScoreCriteria::route('/create'),
            'edit' => Pages\EditScoreCriteria::route('/{record}/edit'),
        ];
    }
}
