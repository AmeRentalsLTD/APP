<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required()
                    ->default('individual'),
                TextInput::make('first_name'),
                TextInput::make('last_name'),
                TextInput::make('company_name'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('address_line1'),
                TextInput::make('address_line2'),
                TextInput::make('city'),
                TextInput::make('postcode'),
                TextInput::make('country')
                    ->required()
                    ->default('United Kingdom'),
                TextInput::make('driving_license_no'),
                DatePicker::make('dob'),
                TextInput::make('nin'),
            ]);
    }
}
