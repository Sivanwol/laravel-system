<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UserRolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        Log::info('User role policy', [config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
        return $user->hasAnyRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Model $model): bool
    {
        return $user->hasAnyRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Model $model): bool
    {
        return $user->hasAnyRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Model $model): bool
    {
        return $user->hasAnyRole([config('constants.system_roles.admin'), config('constants.system_roles.platform_admin')]);
    }
}
