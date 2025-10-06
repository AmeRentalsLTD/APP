@php
    $customer = $invoice->customer;
@endphp

<p>Hello {{ $customer->display_name }},</p>

<p>Please find attached invoice <strong>{{ $invoice->number }}</strong> for your recent vehicle hire with {{ config('finance.company_name') }}.</p>

<p>Total due: <strong>{{ \App\Support\Money::format((float) $invoice->total_gross, $invoice->currency) }}</strong></p>

<p>Kind regards,<br>{{ config('finance.company_name') }} finance team</p>
