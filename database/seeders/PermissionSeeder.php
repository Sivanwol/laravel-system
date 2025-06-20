<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'admin-access',
            'user-management',
            'role-management',
            'system-settings',
            'edit_user_profile',
            'support',
            'billing',
            'business-profile',
            'fleet',
            'delivery-profile',
            'delivery-packages',
            'fleet-limited'
        ];

        // Use the model to create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('Permissions created successfully');
    }
}
