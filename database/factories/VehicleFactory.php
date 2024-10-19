<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravel\Reverb\Loggers\NullLogger;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_type' => $this->faker->randomElement(['e-bicycle', 'bike', 'car', 'van', 'small-truck', 'truck', 'sami-truck', 'bus', 'mini-bus']),
            'other_vehicle_type' => null,
            'license_plate' => $this->faker->unique()->text(40),
            'is_manual' => $this->faker->boolean(),
            'is_electric' => $this->faker->boolean(),
            'max_km_per_run' => $this->faker->randomNumber(),
            'max_weight' => $this->faker->randomNumber(),
            'has_cooling' => $this->faker->boolean(),
        ];
    }
}
