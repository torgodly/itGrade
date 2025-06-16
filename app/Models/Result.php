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
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    //students
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_code', 'code');
    }

    protected function casts(): array
    {
        return [
            'answers' => 'array',
        ];
    }
}
