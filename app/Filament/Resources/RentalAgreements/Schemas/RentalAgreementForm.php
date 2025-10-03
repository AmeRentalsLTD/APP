<?php

namespace App\Filament\Resources\RentalAgreements\Schemas;

use App\Models\Customer;
use App\Models\RentalAgreement;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RentalAgreementForm
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
        $assignment = [];

        if ($includeVehicleField) {
            $assignment[] = Select::make('vehicle_id')
                ->relationship('vehicle', 'registration')
                ->searchable()
                ->preload()
                ->required()
                ->native(false);
        }

        if ($includeCustomerField) {
            $assignment[] = Select::make('customer_id')
                ->relationship('customer', 'display_name')
                ->getOptionLabelFromRecordUsing(
                    fn (Customer $record): string => $record->display_name
                )
                ->searchable()
                ->preload()
                ->required()
                ->native(false);
        }

        $assignment[] = DatePicker::make('start_date')
            ->required();

        $assignment[] = DatePicker::make('end_date')
            ->helperText('Optional. Leave blank for open-ended contracts.');

        return [
            Section::make('Assignment')
                ->schema($assignment)
                ->columns(2),

            Section::make('Commercial Terms')
                ->columns(3)
                ->schema([
                    Select::make('billing_cycle')
                        ->options(self::options(RentalAgreement::BILLING_CYCLES))
                        ->default('weekly')
                        ->native(false)
                        ->required(),
                    TextInput::make('rate_amount')
                        ->label('Rate amount')
                        ->numeric()
                        ->prefix('£')
                        ->required(),
                    TextInput::make('deposit_amount')
                        ->label('Deposit')
                        ->numeric()
                        ->default(500)
                        ->prefix('£'),
                    TextInput::make('notice_days')
                        ->numeric()
                        ->default(14)
                        ->label('Notice period (days)'),
                    TextInput::make('deposit_release_days')
                        ->numeric()
                        ->default(14)
                        ->label('Deposit release (days)'),
                    Select::make('payment_day')
                        ->options(self::options(RentalAgreement::PAYMENT_DAYS))
                        ->default('friday')
                        ->native(false),
                ]),

            Section::make('Policies & Coverage')
                ->columns(3)
                ->schema([
                    Select::make('insurance_option')
                        ->options(self::options(RentalAgreement::INSURANCE_OPTIONS))
                        ->default('company')
                        ->native(false),
                    Select::make('mileage_policy')
                        ->options(self::options(RentalAgreement::MILEAGE_POLICIES))
                        ->default('unlimited')
                        ->native(false),
                    TextInput::make('mileage_cap')
                        ->numeric()
                        ->suffix('miles')
                        ->visible(fn (Get $get): bool => $get('mileage_policy') === 'cap')
                        ->required(fn (Get $get): bool => $get('mileage_policy') === 'cap'),
                    TextInput::make('cleaning_fee')
                        ->numeric()
                        ->prefix('£')
                        ->default(50),
                    TextInput::make('admin_fee')
                        ->numeric()
                        ->prefix('£')
                        ->default(25),
                    Toggle::make('no_smoking')
                        ->inline(false)
                        ->default(true),
                    Toggle::make('tracking_enabled')
                        ->inline(false)
                        ->default(true),
                    Select::make('status')
                        ->options(self::options(RentalAgreement::STATUSES))
                        ->default('active')
                        ->native(false)
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
