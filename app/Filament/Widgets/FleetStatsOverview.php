<?php

namespace App\Filament\Widgets;

use App\Models\FinancialTransaction;
use App\Models\MaintenanceRecord;
use App\Models\RentalAgreement;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Number;

class FleetStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $activeAgreements = RentalAgreement::where('status', 'active')->count();
        $maintenanceInProgress = MaintenanceRecord::whereIn('status', ['scheduled', 'in_progress'])->count();

        $occupancy = $totalVehicles > 0
            ? round(($activeAgreements / $totalVehicles) * 100)
            : 0;

        $periodStart = Carbon::now()->startOfMonth();
        $periodEnd = Carbon::now()->endOfMonth();

        $monthlyIncome = FinancialTransaction::where('type', 'income')
            ->whereBetween('transaction_date', [$periodStart, $periodEnd])
            ->sum('amount');

        $monthlyExpenses = FinancialTransaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$periodStart, $periodEnd])
            ->sum('amount');

        $netCashflow = $monthlyIncome - $monthlyExpenses;

        return [
            Stat::make('Fleet vehicles', Number::format($totalVehicles))
                ->description(Number::format($availableVehicles) . ' available')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),

            Stat::make('Active rentals', Number::format($activeAgreements))
                ->description($occupancy . '% fleet occupancy')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color($occupancy >= 80 ? 'success' : 'warning'),

            Stat::make('Open maintenance', Number::format($maintenanceInProgress))
                ->description('Scheduled or in progress')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color($maintenanceInProgress === 0 ? 'success' : 'danger'),

            Stat::make('Net cashflow (month)', Number::currency($netCashflow, 'GBP'))
                ->description(
                    'Income ' . Number::currency($monthlyIncome, 'GBP') . ' / Expenses ' . Number::currency($monthlyExpenses, 'GBP')
                )
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($netCashflow >= 0 ? 'success' : 'danger'),
        ];
    }
}
