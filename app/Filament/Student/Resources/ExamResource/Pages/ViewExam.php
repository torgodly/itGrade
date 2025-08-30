<?php

namespace App\Filament\Student\Resources\ExamResource\Pages;

use App\Filament\Student\Resources\ExamResource;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class ViewExam extends ViewRecord
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('appeal')
                ->label('Appeal')
                ->translateLabel()
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('reason')
                        ->label('Reason for Appeal')
                        ->required()
                        ->maxLength(65535)
                        ->rows(5)
                        ->columnSpanFull(),
                ])->action(function ($data){
                    $this->record->appeals()->create([
                        'student_id' => auth()->id(),
                        'exam_id' => $this->record->id,
                        'course_id' => $this->record->course_id,
                        'user_id' => $this->record->course->teacher_id,
                        'result_id' => $this->record->results()->where('student_id', auth()->id())->first()->id,
                        'reason' => $data['reason'],
                    ]);

                    Notification::make()
                        ->title(__('Appeal submitted'))
                        ->body(__('Your appeal has been submitted and is under review.'))
                        ->success()
                        ->send();
                })
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
                Section::make(fn($record) => __('Questions for :exam', ['exam' => $record->name]))
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
