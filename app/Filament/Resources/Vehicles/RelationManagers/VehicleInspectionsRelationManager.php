<?php

namespace App\Filament\Resources\Vehicles\RelationManagers;

use App\Filament\Resources\VehicleInspections\Schemas\VehicleInspectionForm;
use App\Models\VehicleInspection;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VehicleInspectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'inspections';

    protected static ?string $title = 'Inspection history';

    public function form(Schema $schema): Schema
    {
        return VehicleInspectionForm::configure(
            $schema,
            includeVehicleField: false
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('inspected_at', 'desc')
            ->columns([
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
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    private static function options(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline($value)])
            ->all();
    }
}
