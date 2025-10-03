<?php

namespace App\Filament\Resources\VehicleInspections;

use App\Filament\Resources\VehicleInspections\Pages\CreateVehicleInspection;
use App\Filament\Resources\VehicleInspections\Pages\EditVehicleInspection;
use App\Filament\Resources\VehicleInspections\Pages\ListVehicleInspections;
use App\Filament\Resources\VehicleInspections\Schemas\VehicleInspectionForm;
use App\Filament\Resources\VehicleInspections\Tables\VehicleInspectionsTable;
use App\Models\VehicleInspection;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class VehicleInspectionResource extends Resource
{
    protected static ?string $model = VehicleInspection::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected static UnitEnum|string|null $navigationGroup = 'Fleet Operations';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Vehicle inspection';

    protected static ?string $pluralModelLabel = 'Vehicle inspections';

    public static function form(Schema $schema): Schema
    {
        return VehicleInspectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleInspectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicleInspections::route('/'),
            'create' => CreateVehicleInspection::route('/create'),
            'edit' => EditVehicleInspection::route('/{record}/edit'),
        ];
    }
}
