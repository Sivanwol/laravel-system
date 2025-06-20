<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function mount(int|string $record, ?array $params = null): void
    {
        parent::mount($record);

        $currentUserIsAdmin = auth()->user()->hasRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
        // Check if edit parameter is present in the request
        $edit = request()->query('edit');

        if ($edit === 'true' || $edit === true || $edit === '1') {
            $this->fillForm();
        }
    }

    protected function fillForm(): void
    {
        $data = $this->record->attributesToArray();

        if ($this->record->userProfile) {
            $data['userProfile'] = $this->record->userProfile->attributesToArray();
        }

        $this->form->fill($data);
    }

    public function save(): void
    {
        $isSelf = auth()->id() === $this->record->id;
        if (!$isSelf && !auth()->user()->hasPermissionTo('edit_user_profile')) {
            Notification::make()
                ->title('Permission denied')
                ->body('You do not have permission to edit this user')
                ->danger()
                ->send();

            return;
        }

        $data = $this->form->getState();

        $this->record->update($data);

        if (isset($data['userProfile']) && is_array($data['userProfile'])) {
            if ($this->record->userProfile) {
                $this->record->userProfile->update($data['userProfile']);
            } else {
                $this->record->userProfile()->create($data['userProfile']);
            }
        }

        Notification::make()
            ->title('User updated')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {

        return [

            Actions\Action::make('editRoles')
                ->label('Change Access')
                ->icon('heroicon-o-shield-check')
                ->color('primary')
                ->visible(function () {
                    // Only users with management permission can change roles
                    if (!auth()->user()->hasPermissionTo('edit_user_profile')) {
                        return false;
                    }

                    $isAdmin = $this->record->hasRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
                    $currentUserIsAdmin = auth()->user()->hasRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
                    $isSelf = auth()->id() === $this->record->id;
                    // No changing yourself
                    if ($isSelf) {
                        return false;
                    }

                    // Super admin users are only visible to other super admins

                    if (!$isAdmin || !$currentUserIsAdmin) {
                        return false;
                    }

                    return true;
                })
                ->form([
                    Forms\Components\CheckboxList::make('roles')
                        ->relationship('roles', 'name', function ($query) {
                            // If the current user is not a super_admin, they cannot assign the super_admin role
                            if (!auth()->user()->hasRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')])) {
                                $query->where('name', '!=', config('constants.system_roles.admin'));
                                $query->where('name', '!=', config('constants.system_roles.platform_admin'));
                            }
                            return $query;
                        })
                        ->label('User Roles')
                        ->helperText('Select the roles for this user')
                        ->columns(2)
                        ->required()
                ])
                ->action(function (array $data): void {
                    $currentIsAdmin = auth()->user()->hasRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
                    $currentUserIsAdmin = auth()->user()->hasRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
                    // Make sure we're not altering super_admin unless we're super_admin
                    if (
                        ($currentIsAdmin || $currentUserIsAdmin) && (
                            in_array(config('constants.system_roles.admin'), $data['roles'] ?? []) || in_array(config('constants.system_roles.platform_admin'), $data['roles'] ?? [])
                        )
                    ) {
                        Notification::make()
                            ->title('Error')
                            ->body('You do not have permission to assign the Super Admin role')
                            ->danger()
                            ->send();
                        return;
                    }

                    $this->record->syncRoles($data['roles'] ?? []);

                    Notification::make()
                        ->title('User roles updated')
                        ->success()
                        ->send();
                })
                ->modalHeading('Change User Access')
                ->modalSubmitActionLabel('Update Roles'),

        ];
    }
}
