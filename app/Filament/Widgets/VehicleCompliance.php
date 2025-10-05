<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use App\Support\VehicleCompliance as VehicleComplianceSupport;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class VehicleCompliance extends TableWidget
{
    protected static ?string $heading = 'Compliance alerts';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->query())
            ->columns([
                TextColumn::make('registration')
                    ->label('Vehicle')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mot_expiry')
                    ->label('MOT expiry')
                    ->badge()
                    ->color(fn ($state): string => VehicleComplianceSupport::color($state))
                    ->formatStateUsing(fn ($state): string => VehicleComplianceSupport::label($state))
                    ->sortable(),
                TextColumn::make('road_tax_due')
                    ->label('Road tax due')
                    ->badge()
                    ->color(fn ($state): string => VehicleComplianceSupport::color($state))
                    ->formatStateUsing(fn ($state): string => VehicleComplianceSupport::label($state))
                    ->sortable(),
            ])
            ->paginated(false);
    }

    protected function query(): Builder
    {
        $today = Carbon::today();
        $threshold = $today->copy()->addDays(Vehicle::COMPLIANCE_ALERT_WINDOW_DAYS);

        return Vehicle::query()
            ->where(function (Builder $query) use ($today, $threshold): void {
                $query
                    ->whereDate('mot_expiry', '<', $today)
                    ->orWhereBetween('mot_expiry', [$today, $threshold])
                    ->orWhereDate('road_tax_due', '<', $today)
                    ->orWhereBetween('road_tax_due', [$today, $threshold]);
            })
            ->orderByRaw('CASE WHEN mot_expiry IS NULL THEN 1 ELSE 0 END')
            ->orderBy('mot_expiry')
            ->orderByRaw('CASE WHEN road_tax_due IS NULL THEN 1 ELSE 0 END')
            ->orderBy('road_tax_due')
            ->limit(12);
    }
}
