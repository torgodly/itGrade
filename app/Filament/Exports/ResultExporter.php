<?php

namespace App\Filament\Exports;

use App\Models\Result;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\HtmlString;

class ResultExporter extends Exporter
{
    protected static ?string $model = Result::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('student.code')
                ->label(__('Student Code')),
            ExportColumn::make('student.name')
                ->label(__('Student Name')),
            ExportColumn::make('course.name')
                ->label(__('Course Name')),
            ExportColumn::make('course.term')
                ->formatStateUsing(fn ($state) => __($state))
                ->label(__('Course Term')),
            ExportColumn::make('course.academic_year')
                ->label(__('Course Academic Year')),
            ExportColumn::make('correct_answers_count')
                ->label(__('Correct Answers'))
                ->formatStateUsing(fn($state, $record) => $state.'/'. count($record->exam->questions)),
            ExportColumn::make('score')
                ->label(__('Score'))
                ->formatStateUsing(fn($state, $record) => $state . ' / ' . $record->exam->total_score),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your results export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';
//        $body = __('Your results export has completed and :successfulRows exported.', [
//            'successfulRows' => number_format($export->getSuccessfulRowsCount()),
//        ]);
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
