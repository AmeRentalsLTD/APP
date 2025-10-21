<?php

namespace App\Filament\Pages;

use App\Models\Vehicle;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use UnitEnum;

class ProfitPerVehicle extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static UnitEnum|string|null $navigationGroup = 'Finance reports';

    protected string $view = 'filament.pages.profit-per-vehicle';

    public ?array $data = [];

    public array $results = [];

    public function mount(): void
    {
        $this->form->fill([
            'from' => Carbon::now()->startOfMonth(),
            'until' => Carbon::now()->endOfMonth(),
        ]);

        $this->runReport();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('from')->label('From'),
            Forms\Components\DatePicker::make('until')->label('Until'),
        ];
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                schema: $this->getFormSchema(),
            ),
        ];
    }

    public function runReport(): void
    {
        $data = $this->form->getState();
        $from = isset($data['from']) ? Carbon::parse($data['from'])->startOfDay() : Carbon::now()->subMonths(1);
        $until = isset($data['until']) ? Carbon::parse($data['until'])->endOfDay() : Carbon::now();

        $vehicles = Vehicle::with([
            'rentals.invoices' => fn ($query) => $query->whereBetween('issue_date', [$from, $until])->where('status', '!=', 'draft'),
            'expenses' => fn ($query) => $query->whereBetween('date', [$from, $until]),
        ])->get();

        $this->results = $vehicles->map(function (Vehicle $vehicle) {
            $income = $vehicle->rentals->flatMap(fn ($rental) => $rental->invoices)->sum('total_gross');
            $expenses = $vehicle->expenses->sum('gross');

            return [
                'vehicle' => $vehicle->registration ?? $vehicle->id,
                'income' => $income,
                'expenses' => $expenses,
                'profit' => $income - $expenses,
            ];
        })->toArray();
    }
}
