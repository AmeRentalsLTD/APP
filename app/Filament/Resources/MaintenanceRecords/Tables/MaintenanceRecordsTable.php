<?php

namespace App\Filament\Resources\MaintenanceRecords\Tables;

use App\Models\MaintenanceRecord;
use Carbon\Carbon;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class MaintenanceRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('scheduled_at', 'asc')
            ->columns([
                TextColumn::make('vehicle.registration')
                    ->label('Vehicle')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'scheduled',
                        'info' => 'in_progress',
                        'success' => 'completed',
                        'gray' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (string $state): string => Str::headline($state))
                    ->sortable(),
                TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->date()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('cost')
                    ->money('GBP')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::options(MaintenanceRecord::STATUSES))
                    ->native(false),
                SelectFilter::make('type')
                    ->options(self::options(MaintenanceRecord::TYPES))
                    ->native(false),
                Filter::make('due_soon')
                    ->label('Due within 14 days')
                    ->query(
                        fn (Builder $query): Builder => $query
                            ->whereIn('status', ['scheduled', 'in_progress'])
                            ->whereBetween('scheduled_at', [
                                Carbon::now()->startOfDay(),
                                Carbon::now()->addDays(14)->endOfDay(),
                            ])
                    ),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }

    private static function options(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn (string $value): array => [$value => Str::headline($value)])
            ->all();
    }
}
