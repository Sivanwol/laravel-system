<?php

namespace App\Filament\Admin\Resources;

use App\Enums\UserRole;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Password;

class UserResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $slug = 'users';
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole(UserRole::adminPanelRoles());
    }

    public static function form(Form $form): Form
    {
        $isCreate = request()->routeIs('filament.admin.resources.users.create');
        $isSelfEdit = false;
        $isViewingOnly = false;

        // In edit mode, check if the user is editing themselves
        if (!$isCreate && ($record = $form->getRecord())) {
            $isSelfEdit = auth()->id() === $record->id;
        }

        // Check if user is in view mode
        $isViewingOnly = !$isCreate && request()->routeIs('filament.admin.resources.users.edit') &&
            !auth()->user()->hasPermissionTo('edit_user_profile') && !$isSelfEdit;

        // User can edit if they have permission or are editing themselves
        $canEdit = auth()->user()->hasPermissionTo('edit_user_profile') || $isSelfEdit;

        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->description('Basic user account information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled($isViewingOnly),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->disabled($isViewingOnly)
                            ->validationMessages([
                                'unique' => 'This email address is already taken.',
                            ]),

                        // Password field only for create form
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn() => $isCreate)
                            ->minLength(8)
                            ->visible($isCreate)
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(fn($state) => bcrypt($state)),
                    ])->columns(2),

                Forms\Components\Section::make('Profile Information')
                    ->description('Additional user profile details')
                    ->visible(!$isCreate)
                    ->schema([
                        Forms\Components\TextInput::make('userProfile.country_code')
                            ->label('Country Code')
                            ->disabled($isViewingOnly)
                            ->maxLength(5),

                        Forms\Components\TextInput::make('userProfile.country_region')
                            ->label('Region')
                            ->disabled($isViewingOnly)
                            ->maxLength(255),

                        Forms\Components\Textarea::make('userProfile.about_me')
                            ->label('About Me')
                            ->disabled($isViewingOnly)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('userProfile.city')
                            ->label('City')
                            ->disabled($isViewingOnly)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('userProfile.address')
                            ->label('Address')
                            ->disabled($isViewingOnly)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('userProfile.zip_code')
                            ->label('Zip Code')
                            ->disabled($isViewingOnly)
                            ->maxLength(20),

                        Forms\Components\Toggle::make('userProfile.allow_preform_deliveries')
                            ->label('Allow Deliveries')
                            ->disabled($isViewingOnly),

                        Forms\Components\TextInput::make('userProfile.building_number')
                            ->label('Building Number')
                            ->disabled($isViewingOnly)
                            ->maxLength(20),

                        Forms\Components\TextInput::make('userProfile.apartment_number')
                            ->label('Apartment Number')
                            ->disabled($isViewingOnly)
                            ->maxLength(20),

                        Forms\Components\TextInput::make('userProfile.floor_number')
                            ->label('Floor Number')
                            ->disabled($isViewingOnly)
                            ->numeric(),

                        Forms\Components\DatePicker::make('userProfile.dob')
                            ->label('Date of Birth')
                            ->disabled($isViewingOnly),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_login')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->label('Roles')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('resetPassword')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Send Password Reset Link')
                    ->modalDescription('This will send a password reset link to the user\'s email.')
                    ->modalSubmitActionLabel('Send Link')
                    ->action(function (User $record) {
                        // Send password reset notification
                        $status = Password::sendResetLink(
                            ['email' => $record->email]
                        );

                        if ($status === Password::RESET_LINK_SENT) {
                            Notification::make()
                                ->title('Password reset link sent')
                                ->body('A password reset link has been sent to ' . $record->email)
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Error')
                                ->body('Unable to send password reset link')
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('viewProfile')
                    ->label('View Profile')
                    ->icon('heroicon-o-eye')
                    ->url(fn(User $record) => static::getUrl('edit', ['record' => $record])),

                Tables\Actions\EditAction::make()
                    ->visible(function (User $record) {
                        $isSuperAdmin = $record->hasRole(UserRole::ADMIN->value);
                        $currentUserIsSuperAdmin = auth()->user()->hasRole(UserRole::ADMIN->value);
                        $isSelf = auth()->id() === $record->id;

                        // Super admin users can only be edited by other super admins
                        if ($isSuperAdmin && !$currentUserIsSuperAdmin) {
                            return false;
                        }

                        return auth()->user()->hasPermissionTo('edit_user_profile') || $isSelf;
                    }),

                Tables\Actions\DeleteAction::make()
                    ->visible(function (User $record) {
                        $isSuperAdmin = $record->hasRole(UserRole::ADMIN->value);
                        $currentUserIsSuperAdmin = auth()->user()->hasRole(UserRole::ADMIN->value);
                        $isSelf = auth()->id() === $record->id;

                        // Super admin users can only be deleted by other super admins
                        if ($isSuperAdmin && !$currentUserIsSuperAdmin) {
                            return false;
                        }

                        // Users cannot delete themselves
                        if ($isSelf) {
                            return false;
                        }

                        return auth()->user()->hasPermissionTo('edit_user_profile');
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'invite' => Pages\InviteUser::route('/invite'),
        ];
    }
}
