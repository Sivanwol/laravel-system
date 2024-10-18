<?
namespace App\Traits;
trait AuthHelperTrait {
    public function hasRole($roles = [], $guard = 'web') {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        return auth($guard)->user()->hasAnyRole($roles);
    }

    public function hasPermission($permissions = [], $guard = 'web') {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }
        return auth($guard)->user()->hasAnyPermission($permissions);
    }

    public function isSuperAdmin($guard = 'web') {
        return $this->hasRole(config('constants.system_roles.platform_admin'), $guard);
    }

    public function isBusiness($guard = 'web') {
        return $this->hasRole(config('constants.system_roles.business'), $guard);
    }

    public function isDelivery($guard = 'web') {
        return $this->hasRole(config('constants.system_roles.delivery'), $guard);
    }
}
