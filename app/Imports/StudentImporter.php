<?php

namespace App\Imports;

use App\Models\Student;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('الاسم')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->label('البريد الالكتروني')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('code')
                ->label('رقم القيد')
                ->requiredMapping()
                ->rules(['required', 'max:255']),


        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your student import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
    public function resolveRecord(): ?Student
    {
        // Student data
        $studentdata = [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'code' => $this->data['code'],
            'password' => bcrypt($this->data['code']), // Set a default password or generate one

        ];
        // Create the student record first
        $student = Student::firstOrCreate(['code' => $studentdata['code']], $studentdata);


        // Return the created student record
        return $student;
    }
}
