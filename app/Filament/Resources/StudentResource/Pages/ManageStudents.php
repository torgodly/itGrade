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
