<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class ProfitPerCustomer extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Finance reports';

    protected static string $view = 'filament.pages.profit-per-customer';

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

        $customers = Customer::with([
            'invoices' => fn ($query) => $query->whereBetween('issue_date', [$from, $until])->where('status', '!=', 'draft'),
            'rentals.vehicle',
        ])->get();

        $this->results = $customers->map(function (Customer $customer) {
            $income = $customer->invoices->sum('total_gross');
            $expenseVehicles = $customer->rentals->pluck('vehicle')->filter();
            $expenses = $expenseVehicles->flatMap(fn ($vehicle) => $vehicle->expenses)->sum('gross');

            return [
                'customer' => $customer->display_name,
                'income' => $income,
                'expenses' => $expenses,
                'profit' => $income - $expenses,
            ];
        })->toArray();
    }
}
