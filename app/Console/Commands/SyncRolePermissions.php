<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use DB;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncRolePermissions extends Command
{
    protected $signature = 'app:sync-role-permissions';
    protected $description = 'Sync permissions to roles using database direct approach';

    public function handle()
    {
        $this->info('Syncing permissions to roles using direct database approach...');

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

            $permissionIds = Permission::whereIn('name', $superAdminPermissions)->pluck('id')->toArray();

            // Direct DB approach
            DB::table('role_has_permissions')->where('role_id', $superAdminRole->id)->delete();

            foreach ($permissionIds as $permissionId) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $superAdminRole->id
                ]);
            }

            $this->info('Super Admin role permissions synced directly');
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

            $permissionIds = Permission::whereIn('name', $platformAdminPermissions)->pluck('id')->toArray();

            // Direct DB approach
            DB::table('role_has_permissions')->where('role_id', $platformAdminRole->id)->delete();

            foreach ($permissionIds as $permissionId) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $platformAdminRole->id
                ]);
            }

            $this->info('Platform Admin role permissions synced directly');
        }

        return Command::SUCCESS;
    }
}
