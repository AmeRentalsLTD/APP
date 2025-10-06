<?php

namespace Tests\Feature;

use App\Jobs\DepositReleaseEligibilityJob;
use App\Jobs\GenerateRecurringInvoicesJob;
use App\Jobs\MarkOverdueInvoicesJob;
use App\Services\InvoiceNumberGenerator;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\Vehicle;
use App\Services\VatCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class FinanceModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_recurring_invoice_creates_invoice(): void
    {
        $customer = \App\Models\Customer::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $rental = Rental::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'frequency' => 'weekly',
            'start_date' => Carbon::today()->subWeeks(2),
        ]);

        config(['finance.weekly_invoice_day' => Carbon::today()->dayOfWeekIso]);

        $job = new GenerateRecurringInvoicesJob();
        $job->handle(app(InvoiceNumberGenerator::class), app(VatCalculator::class));

        $this->assertDatabaseHas('invoices', [
            'rental_id' => $rental->id,
            'status' => 'sent',
        ]);
    }

    public function test_invoice_status_updated_to_paid_when_payments_cover_total(): void
    {
        $invoice = Invoice::factory()->create([
            'subtotal_net' => 100,
            'tax' => 20,
            'total_gross' => 120,
            'status' => 'sent',
        ]);

        Payment::create([
            'invoice_id' => $invoice->id,
            'method' => 'bank',
            'amount_gross' => 120,
            'paid_at' => Carbon::now(),
        ]);

        $invoice->refresh();

        $this->assertEquals('paid', $invoice->status);
    }

    public function test_mark_overdue_invoices_job_updates_status(): void
    {
        $invoice = Invoice::factory()->create([
            'status' => 'sent',
            'issue_date' => Carbon::now()->subDays(10),
            'due_date' => Carbon::now()->subDays(5),
            'total_gross' => 100,
        ]);

        (new MarkOverdueInvoicesJob())->handle();

        $invoice->refresh();

        $this->assertEquals('overdue', $invoice->status);
    }

    public function test_vat_calculator_net_to_gross(): void
    {
        $calculator = new VatCalculator(20);
        $net = 100.0;
        $tax = $calculator->taxAmount($net);
        $gross = $calculator->grossAmount($net);

        $this->assertEquals(20.0, $tax);
        $this->assertEquals(120.0, $gross);
    }

    public function test_profit_per_vehicle_aggregates_income_and_expenses(): void
    {
        $vehicle = Vehicle::factory()->create();
        $rental = Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
        ]);
        $invoice = Invoice::factory()->create([
            'rental_id' => $rental->id,
            'subtotal_net' => 100,
            'tax' => 20,
            'total_gross' => 120,
        ]);
        InvoiceItem::factory()->create([
            'invoice_id' => $invoice->id,
            'line_total_net' => 100,
            'line_tax' => 20,
            'line_total_gross' => 120,
        ]);

        \App\Models\Expense::factory()->create([
            'vehicle_id' => $vehicle->id,
            'gross' => 50,
            'tax' => 10,
            'net' => 40,
        ]);

        $this->assertEquals(120, $vehicle->rentals->first()->invoices->first()->total_gross);
        $this->assertEquals(50, $vehicle->expenses->first()->gross);
    }

    public function test_deposit_release_job_sets_status(): void
    {
        $rental = Rental::factory()->create([
            'end_date' => Carbon::today()->subDays(20),
        ]);

        $deposit = Deposit::factory()->create([
            'rental_id' => $rental->id,
            'status' => 'held',
            'held_at' => Carbon::today()->subMonths(1),
        ]);

        (new DepositReleaseEligibilityJob())->handle();

        $deposit->refresh();

        $this->assertEquals('released', $deposit->status);
    }
}
