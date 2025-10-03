<?php

namespace App\Filament\Resources\Vehicles\Tables;

use App\Models\Vehicle;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registration')
                    ->label('Reg')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('make')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('model')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('year')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'available',
                        'warning' => 'reserved',
                        'info' => 'allocated',
                        'danger' => 'maintenance',
                        'gray' => 'offroad',
                    ])
                    ->formatStateUsing(fn (string $state) => Str::headline($state))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('mot_expiry')
                    ->label('MOT Expiry')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(
                        collect(Vehicle::STATUSES)
                            ->mapWithKeys(fn (string $status) => [$status => Str::headline($status)])
                            ->all()
                    )
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
