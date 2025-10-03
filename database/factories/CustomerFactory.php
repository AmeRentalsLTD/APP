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
        $type = $this->faker->randomElement(Customer::TYPES);

        return [
            'type' => $type,
            'first_name' => $type === 'individual' ? $this->faker->firstName() : null,
            'last_name' => $type === 'individual' ? $this->faker->lastName() : null,
            'company_name' => $type !== 'individual' ? $this->faker->company() : null,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address_line1' => $this->faker->streetAddress(),
            'address_line2' => $this->faker->optional()->secondaryAddress(),
            'city' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'country' => 'United Kingdom',
            'driving_license_no' => $type === 'individual' ? strtoupper($this->faker->bothify('????######??##')) : null,
            'dob' => $type === 'individual' ? $this->faker->dateTimeBetween('-60 years', '-21 years') : null,
            'nin' => $type === 'individual' ? strtoupper($this->faker->bothify('??######?')) : null,
        ];
    }
}
