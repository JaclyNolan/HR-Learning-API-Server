<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trainer>
 */
class TrainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(['External', 'Internal']),
            'education' => $this->faker->sentence,
            'working_place' => $this->faker->company,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
        ];
    }
}
