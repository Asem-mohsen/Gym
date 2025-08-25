<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Services\OnBoarding\AdminOnboardingService;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Admins';

    protected ?string $heading = 'Admins';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_admin', 1)
        ->whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Name')->required(),
                TextInput::make('email')->label('Email')->required()->unique(ignoreRecord: true),
                TextInput::make('phone')->label('Phone')->required(),
                Select::make('roles')->label('Role')->relationship('roles', 'name')->preload()->required()->multiple(),
                Select::make('gender')->label('Gender')->options(['male' => 'Male', 'female' => 'Female'])->preload()->required(),
                Textarea::make('address')->rows(3)->required()->columnSpan(2),
                Toggle::make('is_admin')->label('Is Admin')->onColor('success')->offColor('danger')->inline(false)->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('resend_onboarding')
                    ->label('Resend Onboarding Email')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Resend Onboarding Email')
                    ->modalDescription('This will send a new password setup email to the admin.')
                    ->modalSubmitActionLabel('Send Email')
                    ->action(function (User $record) {
                        $service = new AdminOnboardingService();
                        $success = $service->resendOnboardingEmail($record);
                        
                        if ($success) {
                            Notification::make()
                                ->title('Onboarding email sent successfully')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Failed to send onboarding email')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
