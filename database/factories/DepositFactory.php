<?php

namespace Database\Factories;

use App\Models\Deposit;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Deposit>
 */
class DepositFactory extends Factory
{
    protected $model = Deposit::class;

    public function definition(): array
    {
        $held = Carbon::now()->subMonths(1);

        return [
            'rental_id' => Rental::factory(),
            'amount_net' => $this->faker->randomFloat(2, 150, 400),
            'vat_rate' => 0,
            'held_at' => $held,
            'released_at' => null,
            'status' => 'held',
            'note' => $this->faker->sentence(),
        ];
    }
}
