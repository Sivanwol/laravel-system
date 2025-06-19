<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        $adminRole->syncPermissions([
            'admin-access',
            'user-management',
            'role-management',
            'system-settings',
            'support',
            'billing'
        ]);

        // Create Platform Admin role
        $platformAdminRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.platform_admin'),
            'guard_name' => 'web',
        ]);
        $platformAdminRole->syncPermissions([
            'admin-access',
            'support',
            'billing',
            'user-management'
        ]);

        // Create Delivery role
        $deliveryRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.delivery'),
            'guard_name' => 'web',
        ]);
        $deliveryRole->syncPermissions(['fleet-limited', 'delivery-profile']);

        // Create Business role
        $businessRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.business'),
            'guard_name' => 'web',
        ]);
        $businessRole->syncPermissions(['business-profile', 'fleet']);

        // Create Delivery Business role
        $deliveryBusinessRole = Role::firstOrCreate([
            'name' => config('constants.system_roles.delivery_business'),
            'guard_name' => 'web',
        ]);
        $deliveryBusinessRole->syncPermissions([
            'business-profile',
            'delivery-profile',
            'delivery-packages',
            'fleet'
        ]);
    }
}
