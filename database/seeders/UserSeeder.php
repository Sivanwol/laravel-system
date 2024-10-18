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
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'sivan@wolberg.pro',
            'phone' => '972501234577',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('super-admin');

        $user = User::factory()->create([
            'first_name' => 'Delivery',
            'last_name' => 'User',
            'email' => 'sivan+delivey@wolberg.pro',
            'phone' => '972501234566',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('delivery');

        $user = User::factory()->create([
            'first_name' => 'Business',
            'last_name' => 'User',
            'email' => 'sivan+business@wolberg.pro',
            'phone' => '972501234544',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('business');

        $user = User::factory()->create([
            'first_name' => 'Delivery Business',
            'last_name' => 'User',
            'email' => 'sivan+dbusiness@wolberg.pro',
            'phone' => '972501234555',
            'phone_verified_at' => now(),
            "password" => bcrypt(value: 'password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('delivery-business');
    }
}
