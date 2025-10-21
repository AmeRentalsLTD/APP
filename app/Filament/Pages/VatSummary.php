<?php

namespace App\Filament\Pages;

use App\Models\Expense;
use App\Models\Invoice;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use UnitEnum;

class VatSummary extends Page implements HasForms
{
    use InteractsWithForms;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-scale';

    protected static UnitEnum|string|null $navigationGroup = 'Finance reports';

    protected string $view = 'filament.pages.vat-summary';

    public ?array $data = [];

    public array $figures = [];

    public function mount(): void
    {
        $this->form->fill([
            'from' => Carbon::now()->startOfQuarter(),
            'until' => Carbon::now()->endOfQuarter(),
        ]);

        $this->runReport();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\DatePicker::make('from')->label('From'),
                Forms\Components\DatePicker::make('until')->label('Until'),
            ])
            ->statePath('data');
    }

    public function runReport(): void
    {
        $data = $this->form->getState();
        $from = isset($data['from']) ? Carbon::parse($data['from'])->startOfDay() : Carbon::now()->subMonths(3);
        $until = isset($data['until']) ? Carbon::parse($data['until'])->endOfDay() : Carbon::now();

        $outputTax = Invoice::whereBetween('issue_date', [$from, $until])
            ->where('status', '!=', 'draft')
            ->sum('tax');
        $inputTax = Expense::whereBetween('date', [$from, $until])->sum('tax');

        $this->figures = [
            'output' => $outputTax,
            'input' => $inputTax,
            'payable' => $outputTax - $inputTax,
        ];
    }
}
