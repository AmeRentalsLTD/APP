<?php

namespace App\Filament\Resources\Customers\Schemas;

use App\Models\Customer;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile')
                    ->columns(2)
                    ->schema([
                        Select::make('type')
                            ->options(self::options(Customer::TYPES))
                            ->default('individual')
                            ->required()
                            ->native(false),
                        TextInput::make('company_name')
                            ->maxLength(120),
                        TextInput::make('first_name')
                            ->maxLength(120),
                        TextInput::make('last_name')
                            ->maxLength(120),
                        DatePicker::make('dob')
                            ->label('Date of birth'),
                        TextInput::make('driving_license_no')
                            ->label('Driving licence'),
                        TextInput::make('nin')
                            ->label('National Insurance #')
                            ->maxLength(20),
                    ]),

                Section::make('Contact details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->tel(),
                        TextInput::make('address_line1')
                            ->label('Address line 1'),
                        TextInput::make('address_line2')
                            ->label('Address line 2'),
                        TextInput::make('city'),
                        TextInput::make('postcode'),
                        TextInput::make('country')
                            ->required()
                            ->default('United Kingdom'),
                    ]),
            ]);
    }

    private static function options(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline(str_replace('_', ' ', $value))])
            ->all();
    }
}
