<?php

namespace Tests\Unit\Services;

use App\Models\FinancialTransaction;
use App\Services\FinancialReports\ProfitAndLossReportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfitAndLossReportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_returns_totals_and_breakdown_for_period(): void
    {
        FinancialTransaction::factory()->income()->create([
            'category' => 'rental_income',
            'amount' => 1200,
            'transaction_date' => '2024-02-10',
        ]);

        FinancialTransaction::factory()->income()->create([
            'category' => 'deposit',
            'amount' => 300,
            'transaction_date' => '2024-02-18',
        ]);

        FinancialTransaction::factory()->expense()->create([
            'category' => 'maintenance',
            'amount' => 250,
            'transaction_date' => '2024-02-20',
        ]);

        FinancialTransaction::factory()->expense()->create([
            'category' => 'fuel',
            'amount' => 75,
            'transaction_date' => '2024-03-01',
        ]);

        // Outside of the reporting period and should be ignored.
        FinancialTransaction::factory()->income()->create([
            'category' => 'rental_income',
            'amount' => 999,
            'transaction_date' => '2023-12-31',
        ]);

        $service = new ProfitAndLossReportService();

        $report = $service->generate('2024-02-01', '2024-03-31');

        $this->assertSame([
            'start_date' => '2024-02-01',
            'end_date' => '2024-03-31',
        ], $report['period']);

        $this->assertEquals(1500.0, $report['totals']['income']);
        $this->assertEquals(325.0, $report['totals']['expenses']);
        $this->assertEquals(1175.0, $report['totals']['net']);

        $this->assertSame([
            'deposit' => 300.0,
            'rental_income' => 1200.0,
        ], $report['breakdown']['income']);

        $this->assertSame([
            'fuel' => 75.0,
            'maintenance' => 250.0,
        ], $report['breakdown']['expenses']);
    }

    public function test_monthly_trend_rolls_up_income_and_expenses(): void
    {
        FinancialTransaction::factory()->income()->create([
            'amount' => 800,
            'transaction_date' => '2024-01-05',
        ]);

        FinancialTransaction::factory()->expense()->create([
            'amount' => 150,
            'transaction_date' => '2024-01-18',
        ]);

        FinancialTransaction::factory()->income()->create([
            'amount' => 600,
            'transaction_date' => '2024-02-02',
        ]);

        FinancialTransaction::factory()->expense()->create([
            'amount' => 200,
            'transaction_date' => '2024-02-15',
        ]);

        FinancialTransaction::factory()->income()->create([
            'amount' => 400,
            'transaction_date' => '2024-03-10',
        ]);

        $service = new ProfitAndLossReportService();

        $trend = $service->monthlyTrend(
            Carbon::parse('2024-01-01'),
            Carbon::parse('2024-03-31')
        );

        $this->assertSame([
            [
                'month' => 'Jan 2024',
                'income' => 800.0,
                'expenses' => 150.0,
                'net' => 650.0,
            ],
            [
                'month' => 'Feb 2024',
                'income' => 600.0,
                'expenses' => 200.0,
                'net' => 400.0,
            ],
            [
                'month' => 'Mar 2024',
                'income' => 400.0,
                'expenses' => 0.0,
                'net' => 400.0,
            ],
        ], $trend);
    }

    public function test_generate_normalises_carbon_boundaries_to_whole_days(): void
    {
        FinancialTransaction::factory()->income()->create([
            'amount' => 500,
            'transaction_date' => '2024-05-01',
        ]);

        FinancialTransaction::factory()->expense()->create([
            'amount' => 200,
            'transaction_date' => '2024-05-31',
        ]);

        $service = new ProfitAndLossReportService();

        $report = $service->generate(
            Carbon::parse('2024-05-01 15:45:00'),
            Carbon::parse('2024-05-31 02:15:00'),
        );

        $this->assertSame([
            'start_date' => '2024-05-01',
            'end_date' => '2024-05-31',
        ], $report['period']);

        $this->assertSame(500.0, $report['totals']['income']);
        $this->assertSame(200.0, $report['totals']['expenses']);
        $this->assertSame(300.0, $report['totals']['net']);
    }
}
