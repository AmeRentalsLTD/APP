<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\Vehicle;
use App\Services\InvoiceNumberGenerator;
use App\Services\VatCalculator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FinanceDemoSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::factory()->count(3)->create();
        $vehicles = Vehicle::factory()->count(3)->create();

        $rentals = collect([
            ['frequency' => 'weekly'],
            ['frequency' => 'weekly'],
            ['frequency' => 'monthly'],
        ])->map(function (array $data, int $index) use ($customers, $vehicles) {
            return Rental::factory()->create([
                'customer_id' => $customers[$index]->id,
                'vehicle_id' => $vehicles[$index]->id,
                'frequency' => $data['frequency'],
            ]);
        });

        $generator = app(InvoiceNumberGenerator::class);
        $calculator = new VatCalculator();

        $rentals->each(function (Rental $rental, int $index) use ($generator, $calculator) {
            $issue = Carbon::now()->subDays(($index + 1) * 7);
            $invoice = Invoice::create([
                'customer_id' => $rental->customer_id,
                'rental_id' => $rental->id,
                'number' => $generator->nextNumber($issue),
                'issue_date' => $issue,
                'due_date' => $issue->copy()->addDays(3),
                'status' => $index === 0 ? 'overdue' : 'paid',
                'currency' => 'GBP',
            ]);

            $net = $calculator->round($rental->price_net);
            $tax = $calculator->taxAmount($net, $rental->vat_rate);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'type' => 'rent',
                'description' => 'Vehicle rent – ' . $issue->format('F Y'),
                'qty' => 1,
                'unit_price_net' => $net,
                'vat_rate' => $rental->vat_rate,
                'line_total_net' => $net,
                'line_tax' => $tax,
                'line_total_gross' => $net + $tax,
            ]);

            $invoice->refresh();

            if ($invoice->status === 'paid') {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'method' => 'bank',
                    'amount_gross' => $invoice->total_gross,
                    'paid_at' => $issue->copy()->addDays(1),
                    'reference' => 'DEMO-PAY-' . $invoice->id,
                    'notes' => 'Demo payment',
                ]);
            }

            Deposit::firstOrCreate([
                'rental_id' => $rental->id,
            ], [
                'amount_net' => $rental->deposit_net,
                'vat_rate' => 0,
                'held_at' => $rental->start_date,
                'status' => 'held',
                'note' => 'Security deposit held at rental start.',
            ]);
        });

        Expense::factory()->count(6)->create()->each(function ($expense) use ($vehicles) {
            $expense->update(['vehicle_id' => $vehicles->random()->id]);
        });
    }
}
