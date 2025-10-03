<?php

namespace App\Filament\Resources\MaintenanceRecords\Schemas;

use App\Models\MaintenanceRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MaintenanceRecordForm
{
    public static function configure(Schema $schema, bool $includeVehicleField = true): Schema
    {
        return $schema->components(self::getComponents($includeVehicleField));
    }

    public static function getComponents(bool $includeVehicleField = true): array
    {
        $assignment = [];

        if ($includeVehicleField) {
            $assignment[] = Select::make('vehicle_id')
                ->relationship('vehicle', 'registration')
                ->searchable()
                ->preload()
                ->required()
                ->native(false);
        }

        $assignment[] = TextInput::make('title')
            ->required()
            ->maxLength(120);

        $assignment[] = Select::make('type')
            ->options(self::options(MaintenanceRecord::TYPES))
            ->default('service')
            ->native(false)
            ->required();

        return [
            Section::make('Maintenance details')
                ->schema($assignment)
                ->columns(2),

            Section::make('Scheduling & progress')
                ->columns(3)
                ->schema([
                    Select::make('status')
                        ->options(self::options(MaintenanceRecord::STATUSES))
                        ->default('scheduled')
                        ->native(false)
                        ->required(),
                    DatePicker::make('scheduled_at')
                        ->label('Scheduled date'),
                    DatePicker::make('completed_at')
                        ->label('Completed date'),
                    TextInput::make('odometer')
                        ->numeric()
                        ->suffix('miles'),
                    TextInput::make('cost')
                        ->numeric()
                        ->prefix('£')
                        ->columnSpan(2),
                    TextInput::make('vendor')
                        ->maxLength(120)
                        ->columnSpan(2),
                ]),

            Section::make('Notes')
                ->schema([
                    Textarea::make('notes')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ];
    }

    private static function options(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline($value)])
            ->all();
    }
}
