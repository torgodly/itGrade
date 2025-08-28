<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Imports\StudentImporter;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStudents extends ManageRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make('students')
                ->importer(StudentImporter::class),
//            \EightyNine\ExcelImport\ExcelImportAction::make()
//                ->label('Import Students')
//                ->translateLabel()
////                ->use(StudentImporter::class)
//                ->modelLabel(__('Import Students'))
//                ->modalDescription(__('Import Students'))
//                ->sampleExcel(
//                    sampleData: [
//                        ['name' => 'صهيب الثلثي', 'email' => 'suhibe@gmail.com', 'code' => '123456789'],
//                    ],
//                    fileName: 'sample.xlsx',
//                    sampleButtonLabel: 'Download Sample',
//                )
//
//                ->validateUsing([
//                    'name' => 'required',
//                    'email' => 'required|unique:students,email',
//                    'code' => 'required|max:255',
//                ])
//                ->mutateAfterValidationUsing(
//                    closure: function (array $data): array {
//                        $username = strstr($data['email'], '@', true);
//                        $data['password'] = $data['code'];
//                        return $data;
//                    }
//                )
//                ->color("primary"),
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
