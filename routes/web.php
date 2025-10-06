<?php

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invoices/{invoice}/pdf', function (Invoice $invoice) {
    $pdf = Pdf::loadView('pdf.invoice', [
        'invoice' => $invoice->loadMissing(['customer', 'items', 'payments', 'rental.vehicle']),
    ]);

    return $pdf->download($invoice->number . '.pdf');
})->name('invoices.pdf');
