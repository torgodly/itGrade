<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class ViewExam extends ViewRecord
{
    protected static string $resource = ExamResource::class;
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Exam Details')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('name')
                                ->label('Exam Name'),
                            TextEntry::make('data')
                                ->badge()
                                ->label('Date'),
                            TextEntry::make('course.name')
                                ->label('Course'),
                        ]),
                        TextEntry::make('description')
                            ->label('Description'),
                    ]),
                Section::make(fn($record) => 'Correct Answers (' . count($record->questions) . ' questions)')
                    ->collapsed()
                    ->schema([
                        TableRepeatableEntry::make('questions')
                            ->schema([
                                TextEntry::make('question'),
                                TextEntry::make('answer'),
                                TextEntry::make('score'),
                            ])
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
