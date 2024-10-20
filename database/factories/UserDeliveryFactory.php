<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserDeliveryFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new()->create()->id,
            'profile_image' => fake()->url(),
            'current_driver_license' => fake()->randomElement(['A', 'A1', 'A2', 'B','B1', 'C', 'C1', 'D', 'D1', 'D2', 'D3', 'C+E', 'F','R']),
            'driver_license_issue_date' => fake()->date(),
            'driver_license_expiry_date' => fake()->date(),
            'whatsapp' => fake()->url(),
            'telegram' => fake()->url(),
            'instagram' => fake()->url(),
            'facebook' => fake()->url(),
            'twitter' => fake()->url(),
            'linkedin' => fake()->url(),
            'youtube' => fake()->url(),
            'tiktok' => fake()->url(),
            'about_my_service' => fake()->text(),
            'allow_physical_work' => fake()->boolean(),
        ];
    }
}
