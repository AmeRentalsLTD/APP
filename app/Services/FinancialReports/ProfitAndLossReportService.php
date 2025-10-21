<?php

namespace App\Services\FinancialReports;

use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class ProfitAndLossReportService
{
    /**
     * Generate a profit and loss report for the provided period.
     *
     * @param  CarbonInterface|string  $startDate
     * @param  CarbonInterface|string  $endDate
     * @return array{
     *     period: array{start_date: string, end_date: string},
     *     totals: array{income: float, expenses: float, net: float},
     *     breakdown: array{income: array<string, float>, expenses: array<string, float>}
     * }
     */
    public function generate(CarbonInterface|string $startDate, CarbonInterface|string $endDate): array
    {
        $start = $this->parseBoundaryDate($startDate, false);
        $end = $this->parseBoundaryDate($endDate, true);

        $query = FinancialTransaction::query()
            ->whereBetween('transaction_date', [$start, $end]);

        $typeTotals = (clone $query)
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $categoryBreakdown = (clone $query)
            ->selectRaw('type, category, SUM(amount) as total')
            ->groupBy('type', 'category')
            ->get()
            ->groupBy('type')
            ->map(fn (Collection $rows) => $rows
                ->sortBy('category')
                ->mapWithKeys(fn ($row) => [
                    $row->category => round((float) $row->total, 2),
                ])
                ->all()
            );

        $incomeTotal = round((float) ($typeTotals['income'] ?? 0), 2);
        $expenseTotal = round((float) ($typeTotals['expense'] ?? 0), 2);
        $netTotal = round($incomeTotal - $expenseTotal, 2);

        return [
            'period' => [
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
            ],
            'totals' => [
                'income' => $incomeTotal,
                'expenses' => $expenseTotal,
                'net' => $netTotal,
            ],
            'breakdown' => [
                'income' => $categoryBreakdown->get('income', []),
                'expenses' => $categoryBreakdown->get('expense', []),
            ],
        ];
    }

    /**
     * Calculate the month-on-month trend for the provided period.
     *
     * @return array<int, array{month: string, income: float, expenses: float, net: float}>
     */
    public function monthlyTrend(?CarbonInterface $startDate, ?CarbonInterface $endDate): array
    {
        $query = FinancialTransaction::query()
            ->when($startDate, fn ($q) => $q->whereDate('transaction_date', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('transaction_date', '<=', $endDate))
            ->select(['transaction_date', 'type', 'amount']);

        return $query->get()
            ->groupBy(fn (FinancialTransaction $transaction) => $transaction->transaction_date->format('Y-m'))
            ->sortKeys()
            ->map(function (Collection $transactions) {
                $income = $transactions->where('type', 'income')->sum('amount');
                $expenses = $transactions->where('type', 'expense')->sum('amount');
                $first = $transactions->first();

                $monthLabel = $first?->transaction_date?->copy()->startOfMonth()->format('M Y');

                return [
                    'month' => $monthLabel ?? 'Unknown',
                    'income' => round((float) $income, 2),
                    'expenses' => round((float) $expenses, 2),
                    'net' => round((float) ($income - $expenses), 2),
                ];
            })
            ->values()
            ->all();
    }

    private function parseBoundaryDate(CarbonInterface|string $date, bool $isEnd): CarbonInterface
    {
        $carbon = $date instanceof CarbonInterface
            ? $date->copy()
            : Carbon::parse($date);

        return $isEnd
            ? $carbon->endOfDay()
            : $carbon->startOfDay();
    }
}
