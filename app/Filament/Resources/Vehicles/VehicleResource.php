<?php

namespace App\Filament\Resources\Vehicles;

use App\Filament\Resources\Vehicles\Pages\CreateVehicle;
use App\Filament\Resources\Vehicles\Pages\EditVehicle;
use App\Filament\Resources\Vehicles\Pages\ListVehicles;
use App\Filament\Resources\Vehicles\RelationManagers\FinancialTransactionsRelationManager;
use App\Filament\Resources\Vehicles\RelationManagers\MaintenanceRecordsRelationManager;
use App\Filament\Resources\Vehicles\RelationManagers\RentalAgreementsRelationManager;
use App\Filament\Resources\Vehicles\RelationManagers\VehicleInspectionsRelationManager;
use App\Filament\Resources\Vehicles\Schemas\VehicleForm;
use App\Filament\Resources\Vehicles\Tables\VehiclesTable;
use App\Models\Vehicle;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static UnitEnum|string|null $navigationGroup = 'Fleet Operations';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'registration';

    public static function form(Schema $schema): Schema
    {
        return VehicleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehiclesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RentalAgreementsRelationManager::class,
            MaintenanceRecordsRelationManager::class,
            FinancialTransactionsRelationManager::class,
            VehicleInspectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicles::route('/'),
            'create' => CreateVehicle::route('/create'),
            'edit' => EditVehicle::route('/{record}/edit'),
        ];
    }
}
