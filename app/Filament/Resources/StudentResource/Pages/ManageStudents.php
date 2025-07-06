<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStudents extends ManageRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
//                ->use(StudentImporter::class)
                ->sampleExcel(
                    sampleData: [
                        ['name' => 'صهيب الثلثي', 'email' => 'suhibe@gmail.com', 'code' => '123456789'],
                    ],
                    fileName: 'sample.xlsx',
                    sampleButtonLabel: 'Download Sample',
                )
                ->validateUsing([
                    'name' => 'required',
                    'email' => 'required|email|unique:students,email',
                    'code' => 'required|max:255',
                ])
                ->mutateAfterValidationUsing(
                    closure: function (array $data): array {
                        $username = strstr($data['email'], '@', true);
                        $data['password'] = $username . '@' . $data['code'];
                        return $data;
                    }
                )
                ->color("primary"),
            Actions\CreateAction::make()
                ->createAnother(false)
                ->requiresConfirmation()
                ->modalHeading('Create New Student')
                ->modalIcon('tabler-school')
                ->mutateFormDataUsing(function ($data) {
                    $username = strstr($data['email'], '@', true);
                    $data['password'] = $username . '@' . $data['code'];
                    return $data;
                }),

        ];
    }
}
