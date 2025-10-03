<?php

namespace App\Filament\Resources\FinancialTransactions\Schemas;

use App\Models\Customer;
use App\Models\FinancialTransaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FinancialTransactionForm
{
    public static function configure(
        Schema $schema,
        bool $includeVehicleField = true,
        bool $includeCustomerField = true
    ): Schema {
        return $schema->components(
            self::getComponents(
                includeVehicleField: $includeVehicleField,
                includeCustomerField: $includeCustomerField
            )
        );
    }

    public static function getComponents(
        bool $includeVehicleField = true,
        bool $includeCustomerField = true
    ): array {
        $associations = [];

        if ($includeVehicleField) {
            $associations[] = Select::make('vehicle_id')
                ->relationship('vehicle', 'registration')
                ->searchable()
                ->preload()
                ->native(false);
        }

        if ($includeCustomerField) {
            $associations[] = Select::make('customer_id')
                ->relationship('customer', 'display_name')
                ->getOptionLabelFromRecordUsing(fn (Customer $record): string => $record->display_name)
                ->searchable()
                ->preload()
                ->native(false);
        }

        return [
            Section::make('Transaction details')
                ->schema([
                    Select::make('type')
                        ->options(self::options(FinancialTransaction::TYPES))
                        ->default('income')
                        ->required()
                        ->native(false),
                    Select::make('category')
                        ->options(self::options(FinancialTransaction::CATEGORIES))
                        ->default('rental_income')
                        ->required()
                        ->native(false),
                    DatePicker::make('transaction_date')
                        ->default(now())
                        ->required(),
                    TextInput::make('amount')
                        ->numeric()
                        ->prefix('£')
                        ->required()
                        ->helperText('Use positive amounts; direction is controlled by the type.'),
                    TextInput::make('reference')
                        ->maxLength(120),
                ])
                ->columns(2),

            Section::make('Associations')
                ->schema($associations)
                ->columns(2),

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
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline(str_replace('_', ' ', $value))])
            ->all();
    }
}
