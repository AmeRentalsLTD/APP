<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    protected $model = Rental::class;

    public function definition(): array
    {
        $start = Carbon::now()->subMonths(rand(1, 6))->startOfMonth();
        $frequency = $this->faker->randomElement(['weekly', 'monthly']);

        return [
            'customer_id' => Customer::factory(),
            'vehicle_id' => Vehicle::factory(),
            'price_net' => $this->faker->randomFloat(2, 200, 600),
            'vat_rate' => 20,
            'frequency' => $frequency,
            'deposit_net' => $this->faker->randomFloat(2, 250, 500),
            'start_date' => $start,
            'end_date' => null,
            'notice_days' => 14,
            'status' => 'active',
        ];
    }
}
