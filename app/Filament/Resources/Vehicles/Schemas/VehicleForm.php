<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Models\Vehicle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Basic
            TextInput::make('registration')
                ->label('Registration No')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('make'),
            TextInput::make('model'),
            TextInput::make('variant'),

            TextInput::make('year')
                ->numeric()
                ->minValue(1900)
                ->maxValue((int) date('Y') + 1),

            TextInput::make('mileage')
                ->numeric()
                ->default(0),

            // Compliance
            DatePicker::make('mot_expiry')->label('MOT expiry'),
            DatePicker::make('road_tax_due')->label('Road tax due'),

            // Financials
            TextInput::make('purchase_price')->numeric(),
            TextInput::make('monthly_finance')->numeric(),
            Toggle::make('has_vat')->default(true)->inline(false),

            // Status
            Select::make('status')
                ->options(
                    collect(Vehicle::STATUSES)
                        ->mapWithKeys(fn (string $status) => [$status => Str::headline($status)])
                        ->all()
                )
                ->default('available')
                ->native(false),

            // Notes
            Textarea::make('notes'),
        ]);
    }
}
