<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $issueDate = Carbon::now()->subDays(rand(1, 30));

        return [
            'customer_id' => Customer::factory(),
            'rental_id' => Rental::factory(),
            'number' => 'AME-INV-' . $issueDate->format('Y') . '-' . str_pad((string) $this->faker->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'issue_date' => $issueDate,
            'due_date' => $issueDate->copy()->addDays(3),
            'status' => 'sent',
            'subtotal_net' => 0,
            'tax' => 0,
            'total_gross' => 0,
            'currency' => 'GBP',
        ];
    }
}
