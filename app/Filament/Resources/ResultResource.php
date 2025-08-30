<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResultResource\Pages;
use App\Filament\Resources\ResultResource\RelationManagers;
use App\Models\Result;
use App\Trait\ResourceTranslatedLabels;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class ResultResource extends Resource
{
    use ResourceTranslatedLabels;

    protected static ?string $model = Result::class;

    protected static ?string $navigationIcon = 'tabler-report';

    protected static ?string $navigationGroup = 'Reports';

    public static function canAccess(): bool
    {
        return auth()->user()->type === 'teacher';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('exam_id')
                    ->relationship('exam', 'name')
                    ->required(),
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required(),
                Forms\Components\TextInput::make('answers'),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('pending'),
                Forms\Components\DateTimePicker::make('submitted_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\TextColumn::make('exam.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn($record) => __('Preview Answers for :student', ['student' => $record->student?->name]))
                    ->infolist([
                        TableRepeatableEntry::make('preview_answers')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('question_text'),
                                TextEntry::make('correct_answer')
                                    ->badge(),
                                TextEntry::make('student_answer')
                                    ->badge(),
                                IconEntry::make('is_correct')
                                    ->boolean()
                            ]),


                    ]),
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->fillForm(function ($record) {
                        $questions = collect($record->exam->questions);
                        $answers = collect($record->answers);
                        $answers = $questions->map(function ($question, $index) use ($answers) {
                            $answer = $answers->firstWhere('id', $question['id']);
                            return [
                                'id' => $question['id'],
                                'question' => $question['question'],
                                'answer' => $answer['answer'] ?? 'A',
                                'score' => $question['score'] ?? 0,
                            ];
                        })->values()->toArray();
                        return [
                            'student_id' => $record->student_id,
                            'answers' => $answers,
                        ];
                    })
                    ->form([
                        Forms\Components\Select::make('student_id')
                            ->disabled()
                            ->dehydrated(true)
                            ->relationship('student', 'name')
                            ->searchable(['name', 'code'])
                            ->preload()
                            ->required(),
                        Forms\Components\Repeater::make('answers')
                            ->deletable(false)
                            ->reorderable(false)
                            ->addable(false)
                            ->columnSpanFull()
                            ->live()
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('question')
                                        ->label('Question')
                                        ->readOnly()
                                        ->live()
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\ToggleButtons::make('answer')
                                        ->inline()
                                        ->default('A')
                                        ->options([
                                            'A' => 'A',
                                            'B' => 'B',
                                            'C' => 'C',
                                            'D' => 'D',
                                            'E' => 'E',
                                        ])->required(),
                                    Forms\Components\TextInput::make('score')
                                        ->readOnly()
                                        ->numeric()
                                        ->required()
                                        ->dehydrated(false)
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->default(0),
                                ])
                            ])

                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageResults::route('/'),
        ];
    }
}
