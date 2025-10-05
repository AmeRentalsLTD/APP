<?php

namespace App\Filament\Resources\FinancialTransactions\Tables;

use App\Models\FinancialTransaction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class FinancialTransactionsTable
{
    public static function configure(Table $table): Table
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
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('customer.display_name')
                    ->label('Customer')
                    ->searchable(['customer.company_name', 'customer.first_name', 'customer.last_name'])
                    ->toggleable(),
                TextColumn::make('reference')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Logged at')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(self::options(FinancialTransaction::TYPES))
                    ->native(false),
                SelectFilter::make('category')
                    ->options(self::options(FinancialTransaction::categories()))
                    ->native(false)
                    ->searchable(),
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date): Builder => $q->whereDate('transaction_date', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $q, $date): Builder => $q->whereDate('transaction_date', '<=', $date));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    private static function options(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline(str_replace('_', ' ', $value))])
            ->all();
    }
}
