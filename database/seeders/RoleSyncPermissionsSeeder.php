<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSyncPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Syncing permissions to roles...');

        // Super Admin role permissions
        $superAdminRole = Role::where('name', UserRole::ADMIN->value)->first();
        if ($superAdminRole) {
            $superAdminPermissions = [
                'admin-access',
                'user-management',
                'role-management',
                'system-settings',
                'support',
                'billing'
            ];

            $permissionIds = Permission::whereIn('name', $superAdminPermissions)->pluck('id');
            $superAdminRole->permissions()->sync($permissionIds);
            $this->command->info('Super Admin role permissions synced');
        }

        // Platform Admin role permissions
        $platformAdminRole = Role::where('name', UserRole::PLATFORM_ADMIN->value)->first();
        if ($platformAdminRole) {
            $platformAdminPermissions = [
                'admin-access',
                'user-management',
                'support',
                'billing'
            ];

            $permissionIds = Permission::whereIn('name', $platformAdminPermissions)->pluck('id');
            $platformAdminRole->permissions()->sync($permissionIds);
            $this->command->info('Platform Admin role permissions synced');
        }
    }
}
