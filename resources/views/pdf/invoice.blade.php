<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .logo { font-size: 20px; font-weight: bold; text-transform: uppercase; }
        .section-title { font-weight: bold; text-transform: uppercase; margin-top: 20px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border-bottom: 1px solid #d1d5db; text-align: left; }
        th { background-color: #f3f4f6; text-transform: uppercase; font-size: 11px; }
        .total-table td { border: none; }
        .text-right { text-align: right; }
        .small { font-size: 10px; color: #6b7280; }
        .terms { margin-top: 30px; font-size: 10px; line-height: 1.4; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">{{ config('finance.company_name') }}</div>
        <div>
            <strong>Invoice</strong><br>
            Number: {{ $invoice->number }}<br>
            Issue date: {{ $invoice->issue_date?->format('d M Y') }}<br>
            Due date: {{ $invoice->due_date?->format('d M Y') }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Billed to</div>
        <div>
            <strong>{{ $invoice->customer->display_name }}</strong><br>
            @foreach($invoice->customer->billing_address_lines as $line)
                {{ $line }}<br>
            @endforeach
            VAT No: {{ $invoice->customer->vat_number ?? 'N/A' }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Details</div>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit (net)</th>
                    <th class="text-right">VAT %</th>
                    <th class="text-right">Tax</th>
                    <th class="text-right">Total (gross)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="text-right">{{ number_format((float) $item->qty, 2) }}</td>
                        <td class="text-right">{{ number_format((float) $item->unit_price_net, 2) }}</td>
                        <td class="text-right">{{ $item->vat_rate }}%</td>
                        <td class="text-right">{{ number_format((float) $item->line_tax, 2) }}</td>
                        <td class="text-right">{{ number_format((float) $item->line_total_gross, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <table class="total-table" style="margin-top: 20px;">
        <tr>
            <td class="text-right" style="width: 70%;">Subtotal (net)</td>
            <td class="text-right">{{ number_format((float) $invoice->subtotal_net, 2) }} {{ $invoice->currency }}</td>
        </tr>
        <tr>
            <td class="text-right">VAT</td>
            <td class="text-right">{{ number_format((float) $invoice->tax, 2) }} {{ $invoice->currency }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Total due</strong></td>
            <td class="text-right"><strong>{{ number_format((float) $invoice->total_gross, 2) }} {{ $invoice->currency }}</strong></td>
        </tr>
    </table>

    <div class="section">
        <div class="section-title">VAT summary</div>
        <table>
            <thead>
                <tr>
                    <th>Rate</th>
                    <th class="text-right">Net</th>
                    <th class="text-right">Tax</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items->groupBy('vat_rate') as $rate => $items)
                    <tr>
                        <td>{{ $rate }}%</td>
                        <td class="text-right">{{ number_format((float) $items->sum('line_total_net'), 2) }}</td>
                        <td class="text-right">{{ number_format((float) $items->sum('line_tax'), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="terms">
        <strong>Terms</strong><br>
        Payment is due within 3 days. Bank transfer preferred: Sort Code 00-00-00, Account 00000000. Please quote the invoice number as reference.
    </div>
</body>
</html>
