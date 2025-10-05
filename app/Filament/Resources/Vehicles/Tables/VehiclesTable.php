<?php

namespace App\Filament\Resources\Vehicles\Tables;

use App\Models\Vehicle;
use App\Support\VehicleCompliance as VehicleComplianceSupport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
                    ->label('MOT')
                    ->badge()
                    ->color(fn ($state): string => VehicleComplianceSupport::color($state))
                    ->formatStateUsing(fn ($state): string => VehicleComplianceSupport::label($state))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('road_tax_due')
                    ->label('Road tax')
                    ->badge()
                    ->color(fn ($state): string => VehicleComplianceSupport::color($state))
                    ->formatStateUsing(fn ($state): string => VehicleComplianceSupport::label($state))
                    ->sortable()
                    ->toggleable(),

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
                SelectFilter::make('compliance')
                    ->label('Compliance')
                    ->options([
                        'expired' => 'Expired',
                        'due_soon' => 'Due soon',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if (! $value) {
                            return $query;
                        }

                        $today = Carbon::today();
                        $threshold = $today->copy()->addDays(Vehicle::COMPLIANCE_ALERT_WINDOW_DAYS);

                        return $query->where(function (Builder $subQuery) use ($value, $today, $threshold): void {
                            if ($value === 'expired') {
                                $subQuery
                                    ->whereDate('mot_expiry', '<', $today)
                                    ->orWhereDate('road_tax_due', '<', $today);

                                return;
                            }

                            $subQuery
                                ->whereBetween('mot_expiry', [$today, $threshold])
                                ->orWhereBetween('road_tax_due', [$today, $threshold]);
                        });
                    })
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
