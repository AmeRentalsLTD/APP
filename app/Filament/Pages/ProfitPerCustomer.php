<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use UnitEnum;

class ProfitPerCustomer extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static UnitEnum|string|null $navigationGroup = 'Finance reports';

    protected string $view = 'filament.pages.profit-per-customer';

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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('from')->label('From'),
                Forms\Components\DatePicker::make('until')->label('Until'),
            ])
            ->statePath('data');
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
