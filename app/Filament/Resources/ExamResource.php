<?php

namespace App\Filament\Resources;

use App\Actions\AnalyzePaperAction;
use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use App\Trait\ResourceTranslatedLabels;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Symfony\Component\Process\Process;

class ExamResource extends Resource
{
    use ResourceTranslatedLabels;

    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'tabler-writing';

    protected static ?string $navigationGroup = 'Academics';
    public static function canAccess(): bool
    {
        return auth()->user()->type === 'teacher';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\DateTimePicker::make('date')
                                ->label('Date')
                                ->translateLabel()
                                ->required(),
                            Forms\Components\Select::make('course_id')
                                ->relationship('course', 'name')
                                ->preload()
                                ->searchable()
                                ->required(),
                        ]),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ]),

                ]),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Repeater::make('questions')
                        ->translateLabel()
                        ->hintAction(
                            Action::make('loadQuestions')
                                ->label('Load Questions')
                                ->translateLabel()
                                ->button()
                                ->slideOver()
                                ->form([
                                    Forms\Components\Grid::make()->schema([
//                                        select question from to eg from 1 to question 39 max 70 from allways fixed on 1 and to cant be more than 70
                                        Forms\Components\TextInput::make('from')
                                            ->label('From')
                                            ->readOnly()
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(70),
                                        Forms\Components\TextInput::make('to')
                                            ->label('To')
                                            ->numeric()
                                            ->default(70)
                                            ->required()
                                            ->minValue(1)
                                            ->maxValue(70)
                                            ->validationMessages([
                                                'max' => __('The to value cannot be more than 70'),
                                                'min' => __('The to value must be at least 1'),
                                                'required' => __('The to value is required'),
                                            ])

                                    ]),
                                    //default score
                                    Forms\Components\TextInput::make('default_score')
                                        ->label('Default Score')
                                        ->numeric()
                                        ->default(0)
                                        ->required()
                                        ->columnSpanFull(),
                                    Forms\Components\FileUpload::make('exam_paper')
                                        ->label('Exam Paper')
                                        ->required()
                                        ->acceptedFileTypes(['image/*'])
                                        ->columnSpanFull(),
                                ])
                                ->action(function ($data, $record, Forms\Set $set) {
                                    $process = new Process([
                                        'python',
                                        base_path('app/Python/ocr.py'),
                                        public_path('storage/' . $data['exam_paper']),
                                        storage_path('app/public/loaded_' . basename($data['exam_paper']))
                                    ]);
                                    //dump the command for debugging
                                    $process->run();

                                    if (!$process->isSuccessful()) {
                                        throw new \Exception($process->getErrorOutput());
                                    }

                                    $jsonOutput = $process->getOutput();
                                    $paperJson = json_decode($jsonOutput, true);

                                    $paperJson = collect($paperJson['answers']);
                                    $paperJson = $paperJson->take($data['to']);
                                    $paperJson = $paperJson->map(function ($answer) use ($data) {
                                        return [
                                            'id' => $answer['id'],
                                            'question' => __('Question Number :number', ['number' => $answer['id']]),
                                            'answer' => $answer['answer'],
                                            'score' => $data['default_score'] ?: 0,
                                        ];
                                    });
                                    $set('questions', $paperJson->toArray());
                                })
                        )
                        ->reorderable(false)
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\Grid::make(16)->schema([
                                Forms\Components\TextInput::make('id')
                                    ->label('ID')
                                    ->readOnly()
                                    ->numeric()
                                    ->required()
                                    ->default(fn(Forms\Get $get) => count($get('../../questions')))
                                    ->columnSpan(1), // smallest possible

                                Forms\Components\TextInput::make('question')
                                    ->label('Question')
                                    ->live()
                                    ->default(fn(Forms\Get $get) => __('Question Number :number', ['number' => count($get('../../questions'))]))
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(5),

                                Forms\Components\ToggleButtons::make('answer')
                                    ->translateLabel()
                                    ->inline()
                                    ->default('A')
                                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E'])
                                    ->required()
                                    ->columnSpan(5),

                                Forms\Components\TextInput::make('score')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->default(0)
                                    ->columnSpan(5),
                            ])
                        ])

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.name')
                    ->numeric()
                    ->sortable(),
                //attendance count from results
                Tables\Columns\TextColumn::make('results_count')
                    ->label('Results Count')
                    ->counts('results')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ResultsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
            'view' => Pages\ViewExam::route('/{record}'),
        ];
    }
}
