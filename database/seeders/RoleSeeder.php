<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin role (for Filament access)
        $adminRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.platform_admin'),
            'guard_name' => 'web',
        ]);
        $adminRole->syncPermissions([
            'user-management',
            'view_user_profile',
            'edit_user_profile',
            'view.debuggers',
            'view.telescope',
            'view.pulse',
            'support',
            'billing'
        ]);

        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.admin'),
            'guard_name' => 'web',
        ]);
        $superAdminRole->syncPermissions([
            'user-management',
            'view_user_profile',
            'edit_user_profile',
            'view.telescope',
            'system-settings',
            'support',
            'billing',
            'edit_user_profile'
        ]);

        // Create a Delivery role
        $deliveryRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.delivery'),
            'guard_name' => 'web',
        ]);
        $deliveryRole->syncPermissions([
            'view_user_profile',
            'delivery-profile',
            'delivery-packages',
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
