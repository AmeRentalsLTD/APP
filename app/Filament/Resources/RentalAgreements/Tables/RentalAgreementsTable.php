<?php

namespace App\Filament\Resources\RentalAgreements\Tables;

use App\Models\RentalAgreement;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class RentalAgreementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('start_date', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Contract #')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('vehicle.registration')
                    ->label('Vehicle')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer.display_name')
                    ->label('Customer')
                    ->searchable(['customer.company_name', 'customer.first_name', 'customer.last_name'])
                    ->toggleable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('billing_cycle')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->sortable(),
                TextColumn::make('rate_amount')
                    ->label('Rate')
                    ->money('GBP')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'paused',
                        'danger' => 'ended',
                        'gray' => 'draft',
                    ])
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::optionLabels(RentalAgreement::STATUSES))
                    ->native(false),
                SelectFilter::make('billing_cycle')
                    ->label('Billing cycle')
                    ->options(self::optionLabels(RentalAgreement::BILLING_CYCLES))
                    ->native(false),
                TernaryFilter::make('active')
                    ->label('Active agreements')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->where('status', 'active'),
                        false: fn (Builder $query): Builder => $query->where('status', '!=', 'active'),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    private static function optionLabels(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline($value)])
            ->all();
    }
}
