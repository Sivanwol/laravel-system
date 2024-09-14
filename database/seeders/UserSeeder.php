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
        User::factory()->create([
            'name' => 'admin User',
            'email' => 'sivan@wolberg.pro',
            "password" => bcrypt(value: 'password'),
            'is_backoffice_user' => true,
            'email_verified_at' => now(),
        ]);
    }
}
