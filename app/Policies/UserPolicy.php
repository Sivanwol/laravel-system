<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRole::adminPanelRoles());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole(UserRole::adminPanelRoles());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit_user_profile');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Self-edit is always allowed
        if ($user->id === $model->id) {
            return true;
        }

        // Super admins can only be edited by other super admins
        if ($model->hasRole(UserRole::ADMIN->value) && !$user->hasRole(UserRole::ADMIN->value)) {
            return false;
        }

        return $user->hasPermissionTo('edit_user_profile');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Users cannot delete themselves
        if ($user->id === $model->id) {
            return false;
        }

        // Super admins can only be deleted by other super admins
        if ($model->hasRole(UserRole::ADMIN->value) && !$user->hasRole(UserRole::ADMIN->value)) {
            return false;
        }

        return $user->hasPermissionTo('edit_user_profile');
    }
}
