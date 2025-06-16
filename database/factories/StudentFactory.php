<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'code' => $this->faker->unique()->bothify('STU-####'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // password
            'remember_token' => $this->faker->uuid(),
        ];
    }
}
