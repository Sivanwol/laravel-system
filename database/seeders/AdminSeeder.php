<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@wolberg.pro'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@wolberg.pro',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        $admin->assignRole(UserRole::ADMIN->value);

        $this->command->info('Admin users created successfully!');
        $this->command->info('Admin: admin@wolberg.pro / admin123');
    }
}
