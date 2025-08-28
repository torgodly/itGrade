<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'course_id');
    }

    //get attbute name with academic year and term like "Course Name (2023-2024 - Fall)"
    public function getNameWithYearTermAttribute(): string
    {
        return $this->name . ' (' . $this->academic_year . ' - ' . __($this->term) . ')';
    }
}
