<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'registration' => strtoupper($this->faker->bothify('??##???')),
            'make' => $this->faker->randomElement(['Ford', 'Mercedes-Benz', 'Volkswagen', 'Renault']),
            'model' => $this->faker->randomElement(['Transit', 'Sprinter', 'Crafter', 'Master']),
            'variant' => $this->faker->word(),
            'year' => $this->faker->numberBetween(2015, now()->year),
            'mileage' => $this->faker->numberBetween(10000, 120000),
            'mot_expiry' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'road_tax_due' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'purchase_price' => $this->faker->randomFloat(2, 15000, 45000),
            'monthly_finance' => $this->faker->randomFloat(2, 200, 800),
            'has_vat' => $this->faker->boolean(),
            'status' => $this->faker->randomElement(Vehicle::STATUSES),
            'notes' => $this->faker->sentence(),
        ];
    }
}
