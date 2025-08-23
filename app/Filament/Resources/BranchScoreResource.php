<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchScoreResource\Pages;
use App\Models\BranchScore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

use Filament\Tables\Actions\Action;

class BranchScoreResource extends Resource
{
    protected static ?string $model = BranchScore::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Score Management';

    protected static ?string $navigationLabel = 'Branch Scores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Branch Information')
                    ->schema([
                        Select::make('branch_id')
                            ->label('Branch')
                            ->relationship('branch', 'name')
                            ->required()
                            ->searchable()
                            ->disabled()
                            ->preload(),
                    ]),

                Forms\Components\Section::make('Score Information')
                    ->schema([
                        TextInput::make('score')
                            ->label('Current Score')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100),
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->maxLength(1000),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Review Schedule')
                    ->schema([
                        DateTimePicker::make('last_review_date')
                            ->label('Last Review Date')
                            ->nullable(),
                        DateTimePicker::make('next_review_date')
                            ->label('Next Review Date')
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->label('Branch')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.siteSetting.gym_name')
                    ->label('Gym')
                    ->searchable(),
                TextColumn::make('score')
                    ->label('Score')
                    ->sortable()
                    ->badge()
                    ->color(fn (BranchScore $record): string => $record->score_level_color),
                TextColumn::make('score_level')
                    ->label('Level')
                    ->getStateUsing(fn (BranchScore $record): string => ucfirst($record->score_level))
                    ->badge()
                    ->color(fn (BranchScore $record): string => match($record->score_level) {
                        'excellent' => 'success',
                        'very_good' => 'info',
                        'good' => 'primary',
                        'average' => 'warning',
                        'poor' => 'danger',
                    }),
                TextColumn::make('last_review_date')
                    ->label('Last Review')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('next_review_date')
                    ->label('Next Review')
                    ->dateTime()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\SelectFilter::make('score_level')
                    ->label('Score Level')
                    ->options([
                        'excellent' => 'Excellent (90+)',
                        'very_good' => 'Very Good (80-89)',
                        'good' => 'Good (70-79)',
                        'average' => 'Average (60-69)',
                        'poor' => 'Poor (<60)',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('update_score')
                    ->label('Update Score')
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->form([
                        TextInput::make('new_score')
                            ->label('New Score')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        Textarea::make('change_reason')
                            ->label('Reason for Change')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (BranchScore $record, array $data): void {
                        $oldScore = $record->score;
                        $newScore = $data['new_score'];
                        $changeAmount = $newScore - $oldScore;

                        // Update the score
                        $record->update(['score' => $newScore]);

                        // Record the change in history
                        $record->scoreHistory()->create([
                            'old_score' => $oldScore,
                            'new_score' => $newScore,
                            'change_amount' => $changeAmount,
                            'changed_at' => now(),
                            'change_reason' => $data['change_reason'],
                            'changed_by_id' => \Illuminate\Support\Facades\Auth::id(),
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('score', 'desc');
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
            'index' => Pages\ListBranchScores::route('/'),
            'create' => Pages\CreateBranchScore::route('/create'),

            'edit' => Pages\EditBranchScore::route('/{record}/edit'),
        ];
    }
}
