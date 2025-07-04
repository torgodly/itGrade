<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_id' => \App\Models\Exam::all()->random()->id,
            'student_id' => Student::all()->random()->id,
            'status' => $this->faker->randomElement(['passed', 'failed']),
            'submitted_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'answers' => [
                ['id' => 1, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
                ['id' => 2, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
                ['id' => 3, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
                ['id' => 4, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
                ['id' => 5, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
                ['id' => 6, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
                ['id' => 7, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
                ['id' => 8, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
                ['id' => 9, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
                ['id' => 10, 'answer' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E',])],
            ],
        ];
    }
}
