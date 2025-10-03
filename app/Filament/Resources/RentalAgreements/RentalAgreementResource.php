<?php

namespace App\Filament\Resources\RentalAgreements;

use App\Filament\Resources\RentalAgreements\Pages\CreateRentalAgreement;
use App\Filament\Resources\RentalAgreements\Pages\EditRentalAgreement;
use App\Filament\Resources\RentalAgreements\Pages\ListRentalAgreements;
use App\Filament\Resources\RentalAgreements\Schemas\RentalAgreementForm;
use App\Filament\Resources\RentalAgreements\Tables\RentalAgreementsTable;
use App\Models\RentalAgreement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RentalAgreementResource extends Resource
{
    protected static ?string $model = RentalAgreement::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Fleet Operations';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return RentalAgreementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RentalAgreementsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRentalAgreements::route('/'),
            'create' => CreateRentalAgreement::route('/create'),
            'edit' => EditRentalAgreement::route('/{record}/edit'),
        ];
    }
}
