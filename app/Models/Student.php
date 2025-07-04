<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    //exams
    public function exams(): HasManyThrough
    {
        return $this->hasManyThrough(Exam::class, Result::class, 'student_id', 'id', 'id', 'exam_id');
    }
}
