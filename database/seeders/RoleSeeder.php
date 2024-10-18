<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        sleep(1);
        $role = Role::create([
            'name' => config('constants.system_roles.delivery'),
            'guard_name' => 'web',
        ]);
        $role->syncPermissions(['fleet-limited', 'fleet-limited', 'delivery-profile']);

        $role = Role::create([
            'name' => config('constants.system_roles.business'),
            'guard_name' => 'web',
        ]);
        $role->syncPermissions(['business-profile', 'fleet']);

        $role = Role::create([
            'name' => config('constants.system_roles.delivery_business'),
            'guard_name' => 'web',
        ]);
        $role->syncPermissions(['business-profile', 'delivery-profile', 'delivery-packages', 'fleet']);

        $role = Role::create([
            'name' => config('constants.system_roles.platform_admin'),
            'guard_name' => 'web',
        ]);
        $role->syncPermissions(['support', 'billing', 'user-management']);

    }
}
