<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Services\OnBoarding\AdminOnboardingService;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';
    protected static string | \UnitEnum | null $navigationGroup = 'Management';
    protected static ?string $navigationLabel = 'Admins';

    protected ?string $heading = 'Admins';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('is_admin', 1)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->with(['site.branches.manager', 'site.memberships', 'site.services', 'site.users', 'roles']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                TextColumn::make('phone')->searchable(),
                IconColumn::make('is_gym_owner')
                    ->label('Gym Owner')
                    ->boolean()
                    ->getStateUsing(function (User $record): bool {
                        return $record->site()->exists();
                    })
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                TextColumn::make('site.gym_name')
                    ->label('Gym Name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('No Gym Assigned')
                    ->color(fn ($state) => $state ? 'primary' : 'gray'),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->filters([
                Filter::make('gym_owners')
                    ->label('Gym Owners Only')
                    ->query(fn (Builder $query): Builder => $query->whereHas('site'))
                    ->toggle(),
                
                Filter::make('regular_admins')
                    ->label('Regular Admins Only')
                    ->query(fn (Builder $query): Builder => $query->whereDoesntHave('site'))
                    ->toggle(),
                
                SelectFilter::make('gym')
                    ->label('Filter by Gym')
                    ->relationship('site', 'gym_name')
                    ->searchable()
                    ->preload(),
                
                Filter::make('has_set_password')
                    ->label('Password Set')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('password_set_at'))
                    ->toggle(),
                
                Filter::make('password_not_set')
                    ->label('Password Not Set')
                    ->query(fn (Builder $query): Builder => $query->whereNull('password_set_at'))
                    ->toggle(),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                Action::make('resend_onboarding')
                    ->label('Resend Onboarding Email')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => !$record->hasSetPassword())
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
            ->toolbarActions([

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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
