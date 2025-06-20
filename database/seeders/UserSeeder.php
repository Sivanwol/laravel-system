<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create delivery user if it doesn't exist
        $deliveries = User::factory(4)->create(
            [
                'password' => Hash::make('password123'),
            ]
        );
        foreach ($deliveries as $delivery) {
            $delivery->assignRole(config('constants.system_roles.delivery'));
            $this->command->info('delivery user created successfully!');
            $this->command->info("$delivery->email  / password123");
        }
    }
}
