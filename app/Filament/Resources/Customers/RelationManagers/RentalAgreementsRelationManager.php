<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\RentalAgreements\Schemas\RentalAgreementForm;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RentalAgreementsRelationManager extends RelationManager
{
    protected static string $relationship = 'rentalAgreements';

    protected static ?string $title = 'Rental agreements';

    public function form(Schema $schema): Schema
    {
        return RentalAgreementForm::configure(
            $schema,
            includeCustomerField: false
        );
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('start_date', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Contract #')
                    ->sortable(),
                TextColumn::make('vehicle.registration')
                    ->label('Vehicle')
                    ->searchable()
                    ->sortable(),
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
                    ->options([
                        'active' => 'Active',
                        'paused' => 'Paused',
                        'ended' => 'Ended',
                        'draft' => 'Draft',
                    ])
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
}
