<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            Forms\Components\DateTimePicker::make('data')
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
                        ->reorderable(false)
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\Grid::make(16)->schema([
                                Forms\Components\TextInput::make('id')
                                    ->label('ID')
                                    ->readOnly()
                                    ->numeric()
                                    ->required()
                                    ->default(fn (Forms\Get $get) => count($get('../../questions')))
                                    ->columnSpan(1), // smallest possible

                                Forms\Components\TextInput::make('question')
                                    ->label('Question')
                                    ->live()
                                    ->default(fn (Forms\Get $get) => 'question ' . count($get('../../questions')))
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpan(5),

                                Forms\Components\ToggleButtons::make('answer')
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
                Tables\Columns\TextColumn::make('data')
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
