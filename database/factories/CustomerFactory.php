<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'billing_address' => implode("\n", [
                $this->faker->streetAddress(),
                $this->faker->city(),
                $this->faker->postcode(),
                'United Kingdom',
            ]),
            'vat_number' => 'GB' . $this->faker->numerify('############'),
            'company_number' => $this->faker->numerify('0#######'),
            'notes' => $this->faker->sentence(),
        ];
    }
}
