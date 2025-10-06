<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        $net = $this->faker->randomFloat(2, 20, 150);
        $vatRate = $this->faker->randomElement([0, 5, 20]);
        $tax = round($net * $vatRate / 100, 2);

        return [
            'vehicle_id' => Vehicle::factory(),
            'category' => $this->faker->randomElement(['fuel', 'insurance', 'service', 'road_tax', 'mot', 'repairs']),
            'vendor' => $this->faker->company(),
            'date' => Carbon::now()->subDays(rand(1, 60)),
            'net' => $net,
            'vat_rate' => $vatRate,
            'tax' => $tax,
            'gross' => $net + $tax,
            'reference' => strtoupper($this->faker->bothify('EXP####')),
            'attachment_path' => null,
        ];
    }
}
