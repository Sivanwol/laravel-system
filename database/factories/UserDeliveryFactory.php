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
            'whatsapp' => fake()->url(),
            'telegram' => fake()->url(),
            'instagram' => fake()->url(),
            'facebook' => fake()->url(),
            'twitter' => fake()->url(),
            'linkedin' => fake()->url(),
            'youtube' => fake()->url(),
            'tiktok' => fake()->url(),
            'snapchat' => fake()->url(),
            'about_my_service' => fake()->text(),
            'about_me' => fake()->text(),
            'country' => fake()->country(),
            'country_region' => fake()->state(),
            'city' => fake()->city(),
            'address' => fake()->address(),
            'zip_code' => fake()->postcode(),
        ];
    }
}
