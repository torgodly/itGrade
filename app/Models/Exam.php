<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    protected $guarded = [];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'exam_id');
    }
    //gettotalscore
    public function getTotalScoreAttribute(): int
    {
        return collect($this->questions)->sum('score');
    }

    protected function casts(): array
    {
        return [
            'questions' => 'array',
        ];
    }
    protected static function booted(): void
    {
        static::addGlobalScope('teacher', function (Builder $query) {
            if (auth()->hasUser()) {
                $query->whereHas('course', function (Builder $query) {
                    $query->where('teacher_id', auth()->user()->id);
                });
                // or with a `team` relationship defined:
//                $query->whereBelongsTo(auth()->user ()->team);
            }
        });
    }

}
