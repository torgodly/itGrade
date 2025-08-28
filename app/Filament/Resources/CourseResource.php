<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use App\Trait\ResourceTranslatedLabels;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

class CourseResource extends Resource
{
    use ResourceTranslatedLabels;

    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'tabler-books';

    protected static ?string $navigationGroup = 'Academics';


    public static function canAccess(): bool
    {
        return auth()->user()->type === 'teacher';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('term')
                        ->options([
                            'Fall' => __('Fall'),
                            'Spring' => __('Spring'),
                            'Summer' => __('Summer'),
                        ])
                        ->required(),
                    //academic year select current year and next year to year 2100 option shoud look like 2023-2024
                    Forms\Components\Select::make('academic_year')
                        ->label('Academic Year')
                        ->translateLabel()
                        ->options(function () {
                            $years = [];
                            $currentYear = date('Y');
                            for ($year = $currentYear; $year <= 2100; $year++) {
                                $years["$year-" . ($year + 1)] = "$year-" . ($year + 1);
                            }
                            return $years;
                        })
                        ->default(date('Y') . '-' . (date('Y') + 1))
                        ->required(),
                ]),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('term')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('exams_count')
                    ->label('Exams')
                    ->counts('exams')
                    ->badge()
                    ->sortable(),
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
            RelationManagers\ExamsRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
//            'create' => Pages\CreateCourse::route('/create'),
//            'edit' => Pages\EditCourse::route('/{record}/edit'),
            'view' => Pages\ViewCourse::route('/{record}'),
        ];
    }
}
