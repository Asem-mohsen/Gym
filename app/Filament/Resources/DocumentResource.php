<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

use Filament\Tables\Actions\Action;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Score Management';

    protected static ?string $navigationLabel = 'Documents';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Document Information')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(1000),
                        Select::make('document_type')
                            ->label('Document Type')
                            ->options([
                                'score_document' => 'Score Document',
                                'guidelines' => 'Guidelines',
                                'policies' => 'Policies',
                                'procedures' => 'Procedures',
                                'forms' => 'Forms',
                                'contract' => 'Contract',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->inline(false)
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Document File')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('document')
                            ->label('Document File')
                            ->collection('document')
                            ->acceptedFileTypes([
                                // PDF
                                'application/pdf',
                        
                                // Word (old + new)
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        
                                // Excel (old + new)
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        
                                // CSV + Text
                                'text/csv',
                                'text/plain',
                            ])
                            ->maxSize(10240) // 10MB
                            ->required(),
                    ]),

                Forms\Components\Section::make('Gym Access')
                    ->schema([
                        Select::make('siteSettings')
                            ->label('Available to Gyms')
                            ->multiple()
                            ->relationship('siteSettings', 'gym_name')
                            ->preload()
                            ->searchable()
                            ->helperText('Select which gyms can access this document. Leave empty for all gyms, or select "None" for internal documents.')
                            ->disabled(fn (callable $get) => $get('is_internal')),
                        Toggle::make('is_internal')
                            ->label('Internal Document (Not visible to any gym)')
                            ->helperText('Enable this to make the document internal and not visible to any gym.')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('siteSettings', []);
                                }
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('document_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (Document $record): string => match($record->document_type) {
                        'score_document' => 'primary',
                        'guidelines' => 'success',
                        'policies' => 'warning',
                        'procedures' => 'info',
                        'forms' => 'secondary',
                        'other' => 'danger',
                    }),
                TextColumn::make('document_name')
                    ->label('File Name')
                    ->getStateUsing(fn (Document $record): string => $record->document_name ?? 'No file')
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('media', function ($mediaQuery) use ($search) {
                            $mediaQuery->where('name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('document_size')
                    ->label('File Size'),
                TextColumn::make('available_gyms_count')
                    ->label('Available Gyms')
                    ->badge()
                    ->color(fn (Document $record): string => 
                        $record->is_internal ? 'danger' : 
                        ($record->isAvailableToAllGyms() ? 'success' : 'primary')
                    )
                    ->getStateUsing(fn (Document $record): string => 
                        $record->is_internal ? 'Internal' : $record->available_gyms_count
                    ),
                TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('is_internal')
                    ->label('Internal Documents'),
                Tables\Filters\SelectFilter::make('document_type')
                    ->label('Document Type')
                    ->options([
                        'score_document' => 'Score Document',
                        'guidelines' => 'Guidelines',
                        'policies' => 'Policies',
                        'procedures' => 'Procedures',
                        'forms' => 'Forms',
                        'contract' => 'Contract',
                        'other' => 'Other',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Document $record): string => $record->document_url)
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
