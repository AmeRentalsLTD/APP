<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        $qty = $this->faker->randomFloat(2, 1, 2);
        $unit = $this->faker->randomFloat(2, 150, 400);
        $net = round($qty * $unit, 2);
        $tax = round($net * 0.2, 2);

        return [
            'invoice_id' => Invoice::factory(),
            'type' => 'rent',
            'description' => 'Vehicle rent – ' . $this->faker->monthName(),
            'qty' => $qty,
            'unit_price_net' => $unit,
            'vat_rate' => 20,
            'line_total_net' => $net,
            'line_tax' => $tax,
            'line_total_gross' => $net + $tax,
        ];
    }
}
