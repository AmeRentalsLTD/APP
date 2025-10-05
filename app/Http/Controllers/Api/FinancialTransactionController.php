<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinancialTransactionStoreRequest;
use App\Http\Requests\FinancialTransactionUpdateRequest;
use App\Http\Resources\FinancialTransactionResource;
use App\Models\FinancialTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class FinancialTransactionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $transactions = FinancialTransaction::query()
            ->with([
                'vehicle:id,registration',
                'customer:id,first_name,last_name,company_name',
            ])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(perPage: request('per_page', 15));

        return FinancialTransactionResource::collection($transactions);
    }

    public function store(FinancialTransactionStoreRequest $request): JsonResponse
    {
        $transaction = FinancialTransaction::create($request->validated());

        $transaction->load([
            'vehicle:id,registration',
            'customer:id,first_name,last_name,company_name',
        ]);

        return FinancialTransactionResource::make($transaction)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(FinancialTransaction $financialTransaction): FinancialTransactionResource
    {
        $financialTransaction->load([
            'vehicle:id,registration',
            'customer:id,first_name,last_name,company_name',
        ]);

        return FinancialTransactionResource::make($financialTransaction);
    }

    public function update(FinancialTransactionUpdateRequest $request, FinancialTransaction $financialTransaction): FinancialTransactionResource
    {
        $financialTransaction->fill($request->validated());
        $financialTransaction->save();

        $financialTransaction->load([
            'vehicle:id,registration',
            'customer:id,first_name,last_name,company_name',
        ]);

        return FinancialTransactionResource::make($financialTransaction);
    }

    public function destroy(FinancialTransaction $financialTransaction): JsonResponse
    {
        $financialTransaction->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
