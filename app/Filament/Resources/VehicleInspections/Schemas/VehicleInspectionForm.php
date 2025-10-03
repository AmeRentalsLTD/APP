<?php

namespace App\Filament\Resources\VehicleInspections\Schemas;

use App\Models\VehicleInspection;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VehicleInspectionForm
{
    public static function configure(Schema $schema, bool $includeVehicleField = true): Schema
    {
        return $schema->components(self::getComponents($includeVehicleField));
    }

    public static function getComponents(bool $includeVehicleField = true): array
    {
        $details = [];

        if ($includeVehicleField) {
            $details[] = Select::make('vehicle_id')
                ->relationship('vehicle', 'registration')
                ->searchable()
                ->preload()
                ->required()
                ->native(false);
        }

        $details[] = Select::make('type')
            ->options(self::options(VehicleInspection::TYPES))
            ->required()
            ->native(false)
            ->default('onhire');

        $details[] = DatePicker::make('inspected_at')
            ->label('Inspection date')
            ->required();

        return [
            Section::make('Inspection details')
                ->schema($details)
                ->columns(3),

            Section::make('Condition photos')
                ->columns(3)
                ->schema([
                    self::photoUpload('front_image_path', 'Front'),
                    self::photoUpload('left_image_path', 'Left side'),
                    self::photoUpload('right_image_path', 'Right side'),
                    self::photoUpload('rear_image_path', 'Rear'),
                    self::photoUpload('tyres_image_path', 'Tyres'),
                    self::photoUpload('windscreen_image_path', 'Windscreen'),
                    self::photoUpload('mirrors_image_path', 'Mirrors'),
                ]),

            Section::make('Notes')
                ->schema([
                    Textarea::make('notes')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ];
    }

    private static function photoUpload(string $name, string $label): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->directory('vehicle-inspections')
            ->disk('public')
            ->required();
    }

    private static function options(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline($value)])
            ->all();
    }
}
