<?php

namespace App\Policies;

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
        return $user->hasAnyRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasAnyRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
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
        if ($model->hasAnyRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')])) {
            return false;
        }

        return $user->hasPermissionTo('user-management');
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
        if ($model->hasRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')])) {
            return false;
        }

        return $user->hasPermissionTo('user-management');
    }
}
