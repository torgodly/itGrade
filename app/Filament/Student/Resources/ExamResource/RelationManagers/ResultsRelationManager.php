<?php

namespace App\Filament\Student\Resources\ExamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __(parent::getTitle($ownerRecord, $pageClass));
    } // Declare $isEditable with #[State]
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('student_id', auth()->id());
            })
            ->columns([
                Tables\Columns\TextColumn::make('student.code')
                    ->label('Student Code')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('correct_answers_count')
                    ->label('Correct Answers')
                    ->formatStateUsing(fn($state) => new HtmlString(
                        "<span style='font-size: 1.1em; font-weight: bold'>{$state}</span> / " . count($this->getOwnerRecord()->questions)
                    ))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('score')
                    ->label('Score')
                    ->formatStateUsing(fn($record) => new HtmlString(
                        "<span style='font-size: 1.1em; font-weight: bold'>{$record->score}</span> / " . $this->getOwnerRecord()->total_score)
                    )
                    ->sortable()
                    ->searchable(),

                SpatieMediaLibraryImageColumn::make('exam_paper')
                    ->collection('exam_paper'),
                SpatieMediaLibraryImageColumn::make('exam_answers')
                    ->collection('exam_answers')

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn($record) => __('Preview Answers for :student', ['student' => $record->student?->name]))
                    ->infolist([
                        Tabs::make('Tabs')
                            ->contained(false)
                            ->tabs([
                                Tabs\Tab::make('answers')
                                    ->schema([
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
                                Tabs\Tab::make('exam_papers')
                                    ->schema([
                                        Grid::make()->schema([
                                            SpatieMediaLibraryImageEntry::make('exam_paper')
                                                ->height(600)
                                                ->extraImgAttributes(['class' => 'bg-gray-100'])
                                                ->collection('exam_paper'),
                                            SpatieMediaLibraryImageEntry::make('exam_answers')
                                                ->height(600)
//                                                ->extraImgAttributes(['class'=> 'object-fit'])
                                                ->collection('exam_answers')
                                        ])
                                    ]),
                            ])


                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
