<?php

namespace App\Mail;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoiceEmail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Invoice $invoice)
    {
        $this->subject(sprintf('Invoice %s from %s', $invoice->number, config('finance.company_name')));
    }

    public function build(): self
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $this->invoice->loadMissing(['customer', 'items', 'payments', 'rental.vehicle']),
        ])->output();

        return $this->view('emails.invoice', [
            'invoice' => $this->invoice,
        ])->attachData($pdf, $this->invoice->number . '.pdf', [
            'mime' => 'application/pdf',
        ]);
    }
}
