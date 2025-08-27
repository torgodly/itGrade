<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;

class Student extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory, Notifiable;
    use TwoFactorAuthenticatable;

    protected $guarded = ['id'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

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
