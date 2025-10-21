<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static UnitEnum|string|null $navigationGroup = 'Finance';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label('Email')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Phone')
                    ->maxLength(255),
                Forms\Components\Textarea::make('billing_address')
                    ->label('Billing address')
                    ->rows(3),
                Forms\Components\TextInput::make('vat_number')
                    ->label('VAT number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_number')
                    ->label('Company number')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('vat_number')
                    ->label('VAT number')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }
}
