<?php

namespace Database\Factories;

use App\Models\FinancialTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\FinancialTransaction>
 */
class FinancialTransactionFactory extends Factory
{
    protected $model = FinancialTransaction::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(FinancialTransaction::TYPES);

        return [
            'type' => $type,
            'category' => $this->faker->randomElement(FinancialTransaction::categories($type)),
            'reference' => $this->faker->boolean(70) ? $this->faker->bothify('REF-####') : null,
            'amount' => $this->faker->randomFloat(2, 25, 2500),
            'transaction_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'vehicle_id' => null,
            'customer_id' => null,
            'notes' => $this->faker->boolean(30) ? $this->faker->sentence() : null,
        ];
    }

    public function income(): self
    {
        return $this->state(fn () => [
            'type' => 'income',
            'category' => fake()->randomElement(FinancialTransaction::categories('income')),
        ]);
    }

    public function expense(): self
    {
        return $this->state(fn () => [
            'type' => 'expense',
            'category' => fake()->randomElement(FinancialTransaction::categories('expense')),
        ]);
    }
}
