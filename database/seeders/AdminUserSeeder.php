<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@wolberg.pro'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        if (!$admin->hasRole(UserRole::ADMIN->value)) {
            $admin->assignRole(UserRole::ADMIN->value);
        }

        // Create platform admin if it doesn't exist
        $platformAdmin = User::firstOrCreate(
            ['email' => 'platform@wolberg.pro'],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign platform admin role
        if (!$platformAdmin->hasRole(UserRole::PLATFORM_ADMIN->value)) {
            $platformAdmin->assignRole(UserRole::PLATFORM_ADMIN->value);
        }
    }
}
