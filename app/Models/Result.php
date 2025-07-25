<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Result extends Model implements HasMedia
{

    /** @use HasFactory<\Database\Factories\ResultFactory> */
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = ['id'];
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    //students
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    //correct_answers_count
    public function getCorrectAnswersCountAttribute(): int
    {
        $correctAnswers = collect($this->exam->questions);
        $answers = collect($this->answers);
        return $answers->filter(function ($answer) use ($correctAnswers) {
            return $correctAnswers->contains(function ($correctAnswer) use ($answer) {
                return $correctAnswer['id'] === $answer['id'] && $correctAnswer['answer'] === $answer['answer'];
            });
        })->count();
    }

    //preview_answers /// an attribute to get the question id,, the question text, and the correct answer and the student's answer and if the answer is correct or not
    public function getPreviewAnswersAttribute(): array
    {
        $correctAnswers = collect($this->exam->questions);
        $answers = collect($this->answers);

        return $answers->map(function ($answer) use ($correctAnswers) {
            $correctAnswer = $correctAnswers->where('id', $answer['id'])->first();
            $isCorrect = isset($correctAnswer) && $correctAnswer['answer'] === $answer['answer'];

            return [
                'question_id' => $answer['id'],
                'question_text' => $correctAnswer['question'] ?? 'Unknown Question',
                'correct_answer' => $correctAnswer['answer'] ?? 'Unknown',
                'student_answer' => $answer['answer'],
                'is_correct' => $isCorrect,
            ];
        })->toArray();
    }

    //score
    public function getScoreAttribute(): float
    {
        $correctAnswers = $this->answers;
        $questions = $this->exam->questions;

        $totalScore = 0;

        foreach ($questions as $question) {
            $userAnswer = collect($correctAnswers)->firstWhere('id', $question['id']);
            if ($userAnswer && $userAnswer['answer'] === $question['answer']) {
                $totalScore += $question['score'];
            }
        }

        return $totalScore;
    }


    protected function casts(): array
    {
        return [
            'answers' => 'array',
        ];
    }
}
