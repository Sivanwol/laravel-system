<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'admin User',
            'email' => 'sivan@wolberg.pro',
            "password" => bcrypt(value: 'password'),
            'is_backoffice_user' => true,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('super-admin');
        
        $user = User::factory()->create([
            'name' => 'delivery User',
            'email' => 'sivan+delivey@wolberg.pro',
            "password" => bcrypt(value: 'password'),
            'is_backoffice_user' => false,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('delivery');
        
        $user = User::factory()->create([
            'name' => 'business User',
            'email' => 'sivan+business@wolberg.pro',
            "password" => bcrypt(value: 'password'),
            'is_backoffice_user' => false,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('business');
        
        $user = User::factory()->create([
            'name' => 'delivery-business User',
            'email' => 'sivan+dbusiness@wolberg.pro',
            "password" => bcrypt(value: 'password'),
            'is_backoffice_user' => false,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('delivery-business');
    }
}
