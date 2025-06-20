<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions first
        $permissions = [
            // Admin permissions
            'admin-access',
            'user-management',
            'role-management',
            'system-settings',

            // Business permissions
            'business-profile',
            'fleet',

            // Delivery permissions
            'delivery-profile',
            'delivery-packages',
            'fleet-limited',
            // Support permissions
            'support',
            'billing',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create Admin role (for Filament access)
        $adminRole = Role::firstOrCreate([
            'name' => UserRole::PLATFORM_ADMIN->value,
            'guard_name' => 'web',
        ]);
        $adminRole->syncPermissions([
            'user-management',
            'support',
            'billing'
        ]);

        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate([
            'name' => UserRole::ADMIN->value,
            'guard_name' => 'web',
        ]);
        $superAdminRole->syncPermissions([
            'user-management',
            'system-settings',
            'support',
            'billing',
            'edit_user_profile'
        ]);

        // Create Delivery role
        $deliveryRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.delivery'),
            'guard_name' => 'web',
        ]);

        // Create Business role
        $businessRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.business'),
            'guard_name' => 'web',
        ]);

        // Create Delivery Business role
        $deliveryBusinessRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.delivery_business'),
            'guard_name' => 'web',
        ]);
    }
}
