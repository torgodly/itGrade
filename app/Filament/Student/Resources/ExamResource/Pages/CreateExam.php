<?php

namespace App\Filament\Student\Resources\ExamResource\Pages;

use App\Filament\Student\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExam extends CreateRecord
{
    protected static string $resource = ExamResource::class;
}
