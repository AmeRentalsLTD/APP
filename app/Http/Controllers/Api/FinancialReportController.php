<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfitAndLossRequest;
use App\Models\FinancialTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class FinancialReportController extends Controller
{
    public function __invoke(ProfitAndLossRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        $query = FinancialTransaction::query()
            ->whereBetween('transaction_date', [$startDate, $endDate]);

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

        return response()->json([
            'data' => [
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
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
            ],
        ]);
    }
}
