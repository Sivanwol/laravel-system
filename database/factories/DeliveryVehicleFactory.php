<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessVehicle>
 */
class DeliveryVehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => VehicleFactory::new()->create()->id,
            'user_id' => UserFactory::new()->create()->id,
            'milage' => $this->faker->randomNumber(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'maintenance', 'repair', 'other']),
            'other_status' => null,
            'last_inspection' => $this->faker->dateTime(),
            'last_service' => $this->faker->dateTime(),
        ];
    }
}
