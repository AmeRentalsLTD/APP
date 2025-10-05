<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfitAndLossRequest;
use App\Services\FinancialReports\ProfitAndLossReportService;
use Illuminate\Http\JsonResponse;

class FinancialReportController extends Controller
{
    public function __invoke(
        ProfitAndLossRequest $request,
        ProfitAndLossReportService $reportService
    ): JsonResponse {
        $validated = $request->validated();

        $report = $reportService->generate(
            $validated['start_date'],
            $validated['end_date']
        );

        return response()->json([
            'data' => $report,
        ]);
    }
}
