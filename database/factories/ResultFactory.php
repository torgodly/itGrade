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
            'student_code' => Student::all()->random()->code,
            'score' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['passed', 'failed']),
            'submitted_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'answers' => [
                'question_1' => $this->faker->randomLetter(),
                'question_2' => $this->faker->randomLetter(),
                'question_3' => $this->faker->randomLetter(),
            ],
        ];
    }
}
