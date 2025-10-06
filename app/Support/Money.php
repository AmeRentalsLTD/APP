<?php

namespace App\Support;

class Money
{
    public static function format(float $amount, string $currency = 'GBP'): string
    {
        $formatted = number_format($amount, 2, '.', ',');

        return match ($currency) {
            'GBP' => '£' . $formatted,
            default => $formatted . ' ' . $currency,
        };
    }
}
