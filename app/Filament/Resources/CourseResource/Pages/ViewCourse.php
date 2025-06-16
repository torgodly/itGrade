<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Course Details')
                ->schema([
                    Grid::make()->schema([
                        TextEntry::make('name')
                            ->label('Course Name'),
                        TextEntry::make('term')
                            ->label('Term'),
                    ]),
                    TextEntry::make('description')
                        ->label('Description'),
                ]),
        ]);
    }
}
