<?php

namespace Tests\Feature\Api;

use App\Models\FinancialTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialReportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_profit_and_loss_report_returns_expected_totals_for_period(): void
    {
        FinancialTransaction::factory()->create([
            'type' => 'income',
            'category' => 'rental_income',
            'amount' => 1500,
            'transaction_date' => '2024-03-10',
        ]);

        FinancialTransaction::factory()->create([
            'type' => 'income',
            'category' => 'rental_income',
            'amount' => 500,
            'transaction_date' => '2024-07-02',
        ]);

        FinancialTransaction::factory()->create([
            'type' => 'expense',
            'category' => 'maintenance',
            'amount' => 400,
            'transaction_date' => '2024-04-18',
        ]);

        FinancialTransaction::factory()->create([
            'type' => 'expense',
            'category' => 'fuel',
            'amount' => 100,
            'transaction_date' => '2024-05-05',
        ]);

        // Outside of the selected period and should be ignored.
        FinancialTransaction::factory()->create([
            'type' => 'income',
            'category' => 'rental_income',
            'amount' => 999,
            'transaction_date' => '2023-12-31',
        ]);

        $response = $this->getJson('/api/v1/reports/profit-and-loss?start_date=2024-01-01&end_date=2024-12-31');

        $response->assertOk()
            ->assertJsonPath('data.period.start_date', '2024-01-01')
            ->assertJsonPath('data.period.end_date', '2024-12-31')
            ->assertJsonPath('data.totals.income', 2000)
            ->assertJsonPath('data.totals.expenses', 500)
            ->assertJsonPath('data.totals.net', 1500)
            ->assertJsonPath('data.breakdown.income.rental_income', 2000)
            ->assertJsonPath('data.breakdown.expenses.maintenance', 400)
            ->assertJsonPath('data.breakdown.expenses.fuel', 100);
    }

    public function test_profit_and_loss_report_can_infer_period_from_year(): void
    {
        FinancialTransaction::factory()->income()->create([
            'category' => 'rental_income',
            'amount' => 250,
            'transaction_date' => '2025-01-15',
        ]);

        $response = $this->getJson('/api/v1/reports/profit-and-loss?year=2025');

        $response->assertOk()
            ->assertJsonPath('data.period.start_date', '2025-01-01')
            ->assertJsonPath('data.period.end_date', '2025-12-31')
            ->assertJsonPath('data.totals.income', 250)
            ->assertJsonPath('data.totals.expenses', 0)
            ->assertJsonPath('data.totals.net', 250);
    }

    public function test_profit_and_loss_validation_errors_are_reported(): void
    {
        $this->getJson('/api/v1/reports/profit-and-loss')
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['start_date']);
    }
}
