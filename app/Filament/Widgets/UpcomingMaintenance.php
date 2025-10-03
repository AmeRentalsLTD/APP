<?php

namespace App\Filament\Widgets;

use App\Models\MaintenanceRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UpcomingMaintenance extends TableWidget
{
    protected static ?string $heading = 'Upcoming maintenance';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->query())
            ->columns([
                TextColumn::make('vehicle.registration')
                    ->label('Vehicle')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->sortable()
                    ->limit(40),
                TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'scheduled',
                        'info' => 'in_progress',
                        'success' => 'completed',
                    ])
                    ->formatStateUsing(fn (string $state): string => Str::headline($state)),
                TextColumn::make('vendor')
                    ->toggleable(),
            ])
            ->paginated(false);
    }

    protected function query(): Builder
    {
        return MaintenanceRecord::query()
            ->with('vehicle')
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderByRaw('scheduled_at IS NULL')
            ->orderBy('scheduled_at')
            ->limit(8);
    }
}
