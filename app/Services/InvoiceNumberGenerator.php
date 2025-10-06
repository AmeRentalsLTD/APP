<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Carbon;

class InvoiceNumberGenerator
{
    public function nextNumber(?Carbon $date = null): string
    {
        $date = $date?->copy() ?? Carbon::now();
        $year = $date->format('Y');
        $prefix = "AME-INV-{$year}-";

        $latest = Invoice::where('number', 'like', $prefix . '%')
            ->orderByDesc('number')
            ->value('number');

        $sequence = $latest ? ((int) substr($latest, -4)) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
