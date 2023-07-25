<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trainee>
 */
class TraineeFactory extends Factory
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
            'account' => $this->faker->userName,
            'age' => $this->faker->numberBetween(18, 60),
            'date_of_birth' => $this->faker->date,
            'education' => $this->faker->word,
            'main_programming_language' => $this->faker->word,
            'toeic_score' => $this->faker->randomNumber(3),
            'experience_details' => $this->faker->paragraph,
            'department' => $this->faker->word,
            'location' => $this->faker->city,
        ];
    }
}
