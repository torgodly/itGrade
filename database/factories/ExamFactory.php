<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Math Exam',
                'Science Exam',
                'History Exam',
                'Literature Exam',
                'Geography Exam',
                'Physics Exam',
                'Chemistry Exam',
                'Biology Exam',
            ]),
            'description' => $this->faker->paragraph(),
            'data' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'course_id' => \App\Models\Course::all()->random()->id,
            //correct answers and scores eg . question 1 correct anser is A score is 10
            'correct_answers' => [
                'question_1' => ['answer' => 'A', 'score' => 10],
                'question_2' => ['answer' => 'B', 'score' => 15],
                'question_3' => ['answer' => 'C', 'score' => 20],
                ]
        ];
    }
}
