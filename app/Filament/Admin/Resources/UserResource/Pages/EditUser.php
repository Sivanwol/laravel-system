<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Enums\UserRole;
use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        $isSuperAdmin = $this->record->hasRole(UserRole::ADMIN->value);
        $currentUserIsSuperAdmin = auth()->user()->hasRole(UserRole::ADMIN->value);
        $isSelf = auth()->id() === $this->record->id;

        $canEditOtherUsers = auth()->user()->hasPermissionTo('edit_user_profile');
        $canManageRoles = auth()->user()->hasRole(UserRole::ADMIN->value);

        return [
            Actions\Action::make('editRoles')
                ->label('Manage Roles')
                ->icon('heroicon-o-shield-check')
                ->color('primary')
                ->visible(fn() => $canManageRoles && !($isSuperAdmin && !$currentUserIsSuperAdmin) && !$isSelf)
                ->form([
                    Forms\Components\CheckboxList::make('roles')
                        ->relationship('roles', 'name')
                        ->label('User Roles')
                        ->columns(2)
                        ->helperText('Select the roles for this user')
                        ->required()
                ])
                ->action(function (array $data): void {
                    $this->record->syncRoles($data['roles'] ?? []);

                    Filament\Notifications\Notification::make()
                        ->title('Roles updated')
                        ->success()
                        ->send();
                })
                ->modalHeading('Manage User Roles')
                ->modalSubmitActionLabel('Update Roles'),

            Actions\Action::make('toggleEditMode')
                ->label(fn() => auth()->user()->hasPermissionTo('edit_user_profile') ? 'Enter Edit Mode' : 'View Only')
                ->icon('heroicon-o-pencil-square')
                ->visible(fn() => auth()->user()->hasPermissionTo('edit_user_profile') && !$isSelf)
                ->url(fn() => static::getResource()::getUrl('edit', ['record' => $this->record])),

            Actions\DeleteAction::make()
                ->visible(fn() => $canEditOtherUsers && !$isSelf && !($isSuperAdmin && !$currentUserIsSuperAdmin)),
        ];
    }
}
