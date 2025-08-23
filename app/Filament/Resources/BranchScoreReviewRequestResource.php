<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchScoreReviewRequestResource\Pages;
use App\Models\BranchScoreReviewRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

use Filament\Tables\Actions\Action;

class BranchScoreReviewRequestResource extends Resource
{
    protected static ?string $model = BranchScoreReviewRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Score Management';

    protected static ?string $navigationLabel = 'Review Requests';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Request Information')
                    ->schema([
                        Placeholder::make('branch_info')
                            ->label('Branch')
                            ->content(fn (BranchScoreReviewRequest $record): string => 
                                $record->branchScore?->branch?->name ?? 'N/A'),
                        Placeholder::make('gym_info')
                            ->label('Gym')
                            ->content(fn (BranchScoreReviewRequest $record): string => 
                                $record->branchScore?->branch?->siteSetting?->gym_name ?? 'N/A'),
                        Placeholder::make('requested_by_info')
                            ->label('Requested By')
                            ->content(fn (BranchScoreReviewRequest $record): string => 
                                $record->requestedBy?->name ?? 'N/A'),
                        Placeholder::make('requested_at_info')
                            ->label('Requested At')
                            ->content(fn (BranchScoreReviewRequest $record): string => 
                                $record->requested_at?->format('M d, Y H:i') ?? 'N/A'),
                        Placeholder::make('request_notes_info')
                            ->label('Request Notes')
                            ->content(fn (BranchScoreReviewRequest $record): string => 
                                $record->request_notes ?? 'No notes provided')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Review Information')
                    ->schema([
                        DateTimePicker::make('scheduled_review_date')
                            ->label('Scheduled Review Date')
                            ->nullable(),
                        Textarea::make('review_notes')
                            ->label('Review Notes')
                            ->rows(3)
                            ->maxLength(1000),
                    ])->columns(2),

               Section::make('Supporting Documents')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('supporting_documents')
                            ->label('Supporting Documents')
                            ->collection('supporting_documents')
                            ->downloadable()
                            ->openable()
                            ->deletable(false)
                            ->multiple()
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branchScore.branch.name')
                    ->label('Branch')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branchScore.branch.siteSetting.gym_name')
                    ->label('Gym')
                    ->searchable(),
                TextColumn::make('requestedBy.name')
                    ->label('Requested By')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (BranchScoreReviewRequest $record): string => match($record->status) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                TextColumn::make('requested_at')
                    ->label('Requested')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('scheduled_review_date')
                    ->label('Scheduled Review')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reviewed_at')
                    ->label('Reviewed')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('supporting_documents_count')
                    ->label('Documents')
                    ->getStateUsing(fn (BranchScoreReviewRequest $record): string => $record->getMedia('supporting_documents')->count())
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\TernaryFilter::make('is_reviewed')
                    ->label('Review Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (BranchScoreReviewRequest $record): bool => !$record->is_reviewed)
                    ->form([
                        Textarea::make('review_notes')
                            ->label('Review Notes')
                            ->required()
                            ->rows(3),
                        DateTimePicker::make('scheduled_review_date')
                            ->label('Scheduled Review Date')
                            ->nullable(),
                    ])
                    ->action(function (BranchScoreReviewRequest $record, array $data): void {
                        $record->update([
                            'is_approved' => true,
                            'is_reviewed' => true,
                            'reviewed_at' => now(),
                            'reviewed_by_id' => \Illuminate\Support\Facades\Auth::id(),
                            'review_notes' => $data['review_notes'],
                            'scheduled_review_date' => $data['scheduled_review_date'],
                        ]);
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (BranchScoreReviewRequest $record): bool => !$record->is_reviewed)
                    ->form([
                        Textarea::make('review_notes')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (BranchScoreReviewRequest $record, array $data): void {
                        $record->update([
                            'is_approved' => false,
                            'is_reviewed' => true,
                            'reviewed_at' => now(),
                            'reviewed_by_id' => \Illuminate\Support\Facades\Auth::id(),
                            'review_notes' => $data['review_notes'],
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('requested_at', 'desc');
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
            'index' => Pages\ListBranchScoreReviewRequests::route('/'),
            'view' => Pages\ViewBranchScoreReviewRequest::route('/{record}'),
            'edit' => Pages\EditBranchScoreReviewRequest::route('/{record}/edit'),
        ];
    }
}
