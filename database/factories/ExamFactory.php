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
            'date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'course_id' => \App\Models\Course::all()->random()->id,
            //correct answers and scores eg . question 1 correct anser is A score is 10
            'questions' => [
                [
                    'id' => 1,
                    'question' => 'What is the capital of France?',
                    'answer' => 'A',
                    'score' => 10,
                ],
                [
                    'id' => 2,
                    'question' => 'What is the largest planet in our solar system?',
                    'answer' => 'B',
                    'score' => 10,
                ],
                [
                    'id' => 3,
                    'question' => 'Who wrote "To Kill a Mockingbird"?',
                    'answer' => 'C',
                    'score' => 10,
                ],
                [
                    'id' => 4,
                    'question' => 'What is the chemical symbol for water?',
                    'answer' => 'D',
                    'score' => 10,
                ],
                [
                    'id' => 5,
                    'question' => 'What is the powerhouse of the cell?',
                    'answer' => 'A',
                    'score' => 10,
                ],
                [
                    'id' => 6,
                    'question' => 'What is the speed of light?',
                    'answer' => 'B',
                    'score' => 10,
                ],
                [
                    'id' => 7,
                    'question' => 'What is the largest mammal?',
                    'answer' => 'C',
                    'score' => 10,
                ],
                [
                    'id' => 8,
                    'question' => 'What is the boiling point of water?',
                    'answer' => 'D',
                    'score' => 10,
                ],
                [
                    'id' => 9,
                    'question' => 'What is the capital of Japan?',
                    'answer' => 'A',
                    'score' => 10,
                ],
                [
                    'id' => 10,
                    'question' => 'What is the largest ocean on Earth?',
                    'answer' => 'B',
                    'score' => 10,
                ],
                [
                    'id' => 11,
                    'question' => 'What is the main language spoken in Brazil?',
                    'answer' => 'C',
                    'score' => 10,
                ],
                [
                    'id' => 12,
                    'question' => 'What is the currency of the United Kingdom?',
                    'answer' => 'D',
                    'score' => 10,
                ],
            ]];
    }
}
