<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_user_id' => UserFactory::new()->create()->id,
            'name' => $this->faker->text(40),
            'description' => $this->faker->text(200),
            'website' => $this->faker->url(),
            'facebook' => $this->faker->url(),
            'twitter' => $this->faker->url(),
            'instagram' => $this->faker->url(),
            'linkedin' => $this->faker->url(),
            'youtube' => $this->faker->url(),
            'whatapp' => $this->faker->url(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'country' => $this->faker->countryCode(),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'zip' => $this->faker->postcode(),
            'business-size' => $this->faker->randomElement(['1-10', '11-50', '51-200', '201-500', '501-1000', '1001-5000', '5001-10000', '10001+']),
        ];
    }
}
