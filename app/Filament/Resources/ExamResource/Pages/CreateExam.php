<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExam extends CreateRecord
{
    protected static string $resource = ExamResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['questions'] = collect($data['questions'])->values()->map(function ($question, $index) {
            $question['id'] = $index + 1;
            return $question;
        })->toArray();

        return $data;
    }

}
