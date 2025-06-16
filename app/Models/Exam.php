<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Exam extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ExamFactory> */
    use HasFactory;
    use InteractsWithMedia;

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'exam_id');
    }

    protected function casts(): array
    {
        return [
            'correct_answers' => 'array',
        ];
    }
}
