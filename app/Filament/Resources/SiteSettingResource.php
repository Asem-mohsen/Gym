<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Sites';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Gym Main Data')
                    ->schema([
                        Tabs::make('Tabs')
                            ->tabs([
                                Tabs\Tab::make('English')
                                    ->schema([
                                        TextInput::make('gym_name.en')->label('Gym Name')->required(),
                                        Textarea::make('address.en')->label('Main Address')->rows(3)->columnSpan(2),
                                        Textarea::make('description.en')->label('Description')->rows(3)->columnSpan(2),

                                    ]),
                                Tabs\Tab::make('Arabic')
                                    ->schema([
                                        TextInput::make('gym_name.ar')->label('Gym Name')->required(),
                                        Textarea::make('address.ar')->label('Main Address')->rows(3)->columnSpan(2),
                                        Textarea::make('description.ar')->label('Description')->rows(3)->columnSpan(2),
                                    ]),
                            ])->columnSpan(2),
                        ])
                    ->columns(2),
                Fieldset::make('More Information')
                    ->schema([
                        Select::make('owner_id')->label('Owner')
                            ->relationship('owner', 'name', function ($query) {
                                $query->whereHas('roles', function ($q) { 
                                    $q->where('name', 'admin');
                                })
                                ->whereDoesntHave('site', function ($q) {
                                    $q->where('id', '!=', request()->route('record')?->id ?? 0);
                                });
                            })
                            ->hiddenOn('edit')
                            ->preload()
                            ->required()
                            ->searchable(),
                        TextInput::make('size')->label('Gym Employee Size'),
                        TextInput::make('contact_email')->label('Contact Email')->required()->email(),
                        TextInput::make('site_url')->label('Site Url')->url(),
                    ])->columns(2),
                
                Fieldset::make('Media')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make("gym_logo")->label('Gym Logo')->collection("gym_logo")->required(),
                        SpatieMediaLibraryFileUpload::make("favicon")->label('Fav Icon')->collection("favicon")->required(),
                        SpatieMediaLibraryFileUpload::make("email_logo")->label('Email Logo')->collection("email_logo"),
                        SpatieMediaLibraryFileUpload::make("footer_logo")->label('Footer Logo')->collection("footer_logo"),
                    ])->columns(2),

                Fieldset::make('Social Media')
                    ->schema([
                        TextInput::make('facebook_url')->label('Facebook URL')->url(),
                        TextInput::make('x_url')->label('X URL')->url(),
                        TextInput::make('instagram_url')->label('Instagram URL')->url(),
                    ])->columns(2),

                Fieldset::make('Contract Document')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make("contract_document")->label('Contract Document')->collection("contract_document")
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
                            ->helperText('Upload the contract document for this gym. This will be automatically linked to a document record.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('gym_name')->searchable(),
                TextColumn::make('owner.name')->label('Owner Name')->searchable(),
                TextColumn::make('owner.email')->label('Owner Email')->searchable(),
                TextColumn::make('address')->sortable()->words(5),
                TextColumn::make('site_url')->sortable()->default('---'),
                TextColumn::make('created_at')->sortable()->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'view' => Pages\ViewSiteSetting::route('/{record}'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
