<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $paidAt = Carbon::now()->subDays(rand(0, 10));
        $amount = $this->faker->randomFloat(2, 100, 600);

        return [
            'invoice_id' => Invoice::factory(),
            'method' => $this->faker->randomElement(['bank', 'cash', 'other']),
            'amount_gross' => $amount,
            'paid_at' => $paidAt,
            'reference' => strtoupper($this->faker->bothify('PAY####')),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
