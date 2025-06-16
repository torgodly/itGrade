<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    /** @use HasFactory<\Database\Factories\ResultFactory> */
    use HasFactory;

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    protected function casts(): array
    {
        return [
            'answers' => 'array',
        ];
    }
}
