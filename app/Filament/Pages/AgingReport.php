<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class AgingReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Finance reports';

    protected static string $view = 'filament.pages.aging-report';

    public array $buckets = [];

    public function mount(): void
    {
        $this->calculate();
    }

    public function calculate(): void
    {
        $today = Carbon::today();
        $buckets = [
            '0-30' => ['total' => 0, 'invoices' => []],
            '31-60' => ['total' => 0, 'invoices' => []],
            '61-90' => ['total' => 0, 'invoices' => []],
            '90+' => ['total' => 0, 'invoices' => []],
        ];

        $invoices = Invoice::with('customer', 'payments')
            ->whereNotIn('status', ['paid'])
            ->get();

        foreach ($invoices as $invoice) {
            $balance = $invoice->balance;
            if ($balance <= 0) {
                continue;
            }

            $age = $invoice->due_date ? $invoice->due_date->diffInDays($today, false) : 0;
            if ($age <= 30) {
                $bucketKey = '0-30';
            } elseif ($age <= 60) {
                $bucketKey = '31-60';
            } elseif ($age <= 90) {
                $bucketKey = '61-90';
            } else {
                $bucketKey = '90+';
            }

            $buckets[$bucketKey]['total'] += $balance;
            $buckets[$bucketKey]['invoices'][] = [
                'number' => $invoice->number,
                'customer' => $invoice->customer?->display_name,
                'due_date' => optional($invoice->due_date)->format('d M Y'),
                'balance' => $balance,
            ];
        }

        $this->buckets = $buckets;
    }
}
