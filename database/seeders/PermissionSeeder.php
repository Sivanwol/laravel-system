<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'fleet',
            'fleet-limited',
            'business-profile',
            'delivery-profile',
            'delivery-packages',
            'support',
            'billing',
            'user-management',
        ];
        $values = [];
        foreach ($permissions as $permission) {
            array_push($values, ['name' => $permission, 'guard_name' => 'web']);
        }
        DB::table('permissions')->insert($values);
    }
}
