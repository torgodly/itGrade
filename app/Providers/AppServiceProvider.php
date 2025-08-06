<?php

namespace App\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Entry;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Table::configureUsing(function (Table $table) {
            $table->defaultSort('created_at', 'desc')
                ->striped();
        });

//        TextInput::configureUsing(function (TextInput $input) {
//            $input->translateLabel();
//        });
//
//        Select::configureUsing(function (Select $input) {
//            $input->translateLabel();
//        });
//
//        DateTimePicker::configureUsing(function (DateTimePicker $input) {
//            $input->translateLabel();
//        });
//
//        Textarea::configureUsing(function (Textarea $input) {
//            $input->translateLabel();
//        });
//
        Field::configureUsing(function (Field $field) {
            $field->translateLabel();
        });
        Column::configureUsing(function (Column $column) {
            $column->translateLabel();
        });

        Entry::configureUsing(function (Entry $entry) {
            $entry->translateLabel();
        });

    }
}
