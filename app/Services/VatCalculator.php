<?php

namespace App\Services;

class VatCalculator
{
    public function __construct(private int $defaultRate = 20)
    {
    }

    public function netTotal(float $unitNet, float $quantity): float
    {
        return $this->round($unitNet * $quantity);
    }

    public function taxAmount(float $netAmount, ?int $rate = null): float
    {
        $rate = $rate ?? $this->defaultRate;

        return $this->round($netAmount * ($rate / 100));
    }

    public function grossAmount(float $netAmount, ?int $rate = null): float
    {
        return $this->round($netAmount + $this->taxAmount($netAmount, $rate));
    }

    public function netFromGross(float $grossAmount, ?int $rate = null): float
    {
        $rate = $rate ?? $this->defaultRate;
        $divider = 1 + ($rate / 100);

        return $this->round($grossAmount / $divider);
    }

    public function taxFromGross(float $grossAmount, ?int $rate = null): float
    {
        $net = $this->netFromGross($grossAmount, $rate);

        return $this->round($grossAmount - $net);
    }

    public function round(float $value): float
    {
        return round($value, 2);
    }
}
