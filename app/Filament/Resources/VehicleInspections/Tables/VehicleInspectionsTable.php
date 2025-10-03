<?php

namespace App\Filament\Resources\VehicleInspections\Tables;

use App\Models\VehicleInspection;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VehicleInspectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('inspected_at', 'desc')
            ->columns([
                TextColumn::make('vehicle.registration')
                    ->label('Vehicle')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->sortable(),
                TextColumn::make('inspected_at')
                    ->label('Inspected on')
                    ->date()
                    ->sortable(),
                ImageColumn::make('front_image_path')
                    ->label('Front')
                    ->disk('public')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Logged at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(self::options(VehicleInspection::TYPES))
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    private static function options(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline($value)])
            ->all();
    }
}
