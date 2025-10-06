<?php

namespace App\Jobs;

use App\Mail\SendInvoiceEmail;
use App\Models\Invoice;
use App\Models\Rental;
use App\Services\InvoiceNumberGenerator;
use App\Services\VatCalculator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class GenerateRecurringInvoicesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(InvoiceNumberGenerator $generator, VatCalculator $vatCalculator): void
    {
        $today = Carbon::today();

        Rental::active()->with(['customer', 'vehicle'])->get()->each(function (Rental $rental) use ($today, $generator, $vatCalculator) {
            if (! $rental->dueToday($today)) {
                return;
            }

            $invoice = Invoice::create([
                'customer_id' => $rental->customer_id,
                'rental_id' => $rental->id,
                'number' => $generator->nextNumber($today),
                'issue_date' => $today,
                'due_date' => $today->copy()->addDays(3),
                'status' => 'sent',
                'currency' => config('finance.currency', 'GBP'),
            ]);

            $description = sprintf('Vehicle rent – %s', $this->periodLabel($rental, $today));

            $net = $vatCalculator->round((float) $rental->price_net);
            $tax = $vatCalculator->taxAmount($net, (int) $rental->vat_rate);

            $invoice->items()->create([
                'type' => 'rent',
                'description' => $description,
                'qty' => 1,
                'unit_price_net' => $net,
                'vat_rate' => $rental->vat_rate,
                'line_total_net' => $net,
                'line_tax' => $tax,
                'line_total_gross' => $net + $tax,
            ]);

            $invoice->refresh();

            Mail::to($rental->customer->email)->queue(new SendInvoiceEmail($invoice));
        });
    }

    private function periodLabel(Rental $rental, Carbon $date): string
    {
        if ($rental->frequency === 'weekly') {
            $start = $date->copy()->startOfWeek();
            $end = $date->copy()->endOfWeek();

            return $start->format('d M Y') . ' – ' . $end->format('d M Y');
        }

        return $date->format('F Y');
    }
}
