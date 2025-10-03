<?php

namespace App\Filament\Widgets;

use App\Models\FinancialTransaction;
use App\Models\MaintenanceRecord;
use App\Models\RentalAgreement;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use NumberFormatter;

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
            Stat::make('Fleet vehicles', $this->formatNumber($totalVehicles))
                ->description($this->formatNumber($availableVehicles) . ' available')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),

            Stat::make('Active rentals', $this->formatNumber($activeAgreements))
                ->description($occupancy . '% fleet occupancy')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color($occupancy >= 80 ? 'success' : 'warning'),

            Stat::make('Open maintenance', $this->formatNumber($maintenanceInProgress))
                ->description('Scheduled or in progress')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color($maintenanceInProgress === 0 ? 'success' : 'danger'),

            Stat::make('Net cashflow (month)', $this->formatCurrency($netCashflow, 'GBP'))
                ->description(
                    'Income ' . $this->formatCurrency($monthlyIncome, 'GBP') . ' / Expenses ' . $this->formatCurrency($monthlyExpenses, 'GBP')
                )
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($netCashflow >= 0 ? 'success' : 'danger'),
        ];
    }

    private function formatNumber(int|float $value): string
    {
        return number_format($value);
    }

    private function formatCurrency(int|float $value, string $currency): string
    {
        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

        $formatted = $formatter->formatCurrency($value, $currency);

        if ($formatted !== false) {
            return $formatted;
        }

        return sprintf('%s %s', $currency, number_format($value, 2));
    }
}
