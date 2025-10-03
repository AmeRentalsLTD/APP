<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\FinancialTransactions\Schemas\FinancialTransactionForm;
use App\Models\FinancialTransaction;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FinancialTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'financialTransactions';

    protected static ?string $title = 'Financial history';

    public function form(Schema $schema): Schema
    {
        return FinancialTransactionForm::configure(
            $schema,
            includeCustomerField: false
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('transaction_date', 'desc')
            ->columns([
                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'income',
                        'danger' => 'expense',
                    ])
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->sortable(),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Str::headline(str_replace('_', ' ', $state)))
                    ->sortable(),
                TextColumn::make('amount')
                    ->money('GBP')
                    ->sortable(),
                TextColumn::make('vehicle.registration')
                    ->label('Vehicle')
                    ->toggleable(),
                TextColumn::make('reference')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(self::options(FinancialTransaction::TYPES))
                    ->native(false),
                SelectFilter::make('category')
                    ->options(self::options(FinancialTransaction::CATEGORIES))
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
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline(str_replace('_', ' ', $value))])
            ->all();
    }
}
