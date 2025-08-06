<?php
namespace App\Trait;

use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

/**
 * @mixin Resource
 * */
trait ResourceTranslatedLabels
{
    public static function getModelLabel(): string
    {
        // Convert camel case to a readable format
        $singular = Str::headline(class_basename(self::getModel()));

        return __($singular);
    }

    public static function getPluralLabel(): ?string
    {
        // Convert to singular first, then plural, and handle headline formatting
        $singular = Str::headline(class_basename(self::getModel()));
        $plural = Str::plural($singular);

        return __($plural);
    }

    public static function getNavigationLabel(): string
    {
        // Convert camel case to a readable format for navigation
        $navigationLabel = Str::headline(parent::getNavigationLabel());

        return __($navigationLabel);
    }

    public function getHeading(): string|Htmlable
    {
        // Convert heading text to a human-readable format
        $heading = Str::headline(parent::getHeading());

        return __($heading);
    }

    /**
     * @return string|null
     */
    public static function getNavigationGroup(): ?string
    {
        return __(self::$navigationGroup);
    }
}
