<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\FinancialReportController;
use App\Http\Controllers\Api\FinancialTransactionController;
use App\Http\Controllers\Api\RentalAgreementController;
use App\Http\Controllers\Api\VehicleInspectionController;
use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::apiResource('vehicles', VehicleController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('rental-agreements', RentalAgreementController::class);
    Route::apiResource('vehicle-inspections', VehicleInspectionController::class);
    Route::apiResource('financial-transactions', FinancialTransactionController::class);
    Route::get('reports/profit-and-loss', FinancialReportController::class)->name('reports.profit-and-loss');
});
