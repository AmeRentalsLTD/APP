<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\RentalAgreement;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RentalAgreement>
 */
class RentalAgreementFactory extends Factory
{
    protected $model = RentalAgreement::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $billingCycle = $this->faker->randomElement(RentalAgreement::BILLING_CYCLES);
        $mileagePolicy = $this->faker->randomElement(RentalAgreement::MILEAGE_POLICIES);

        return [
            'vehicle_id' => Vehicle::factory(),
            'customer_id' => Customer::factory(),
            'start_date' => $startDate,
            'end_date' => $this->faker->optional()->dateTimeBetween($startDate, '+6 months'),
            'billing_cycle' => $billingCycle,
            'rate_amount' => $billingCycle === 'weekly'
                ? $this->faker->randomFloat(2, 250, 600)
                : $this->faker->randomFloat(2, 900, 2200),
            'deposit_amount' => $this->faker->randomFloat(2, 250, 1000),
            'notice_days' => $this->faker->numberBetween(7, 28),
            'deposit_release_days' => $this->faker->numberBetween(7, 28),
            'insurance_option' => $this->faker->randomElement(RentalAgreement::INSURANCE_OPTIONS),
            'mileage_policy' => $mileagePolicy,
            'mileage_cap' => $mileagePolicy === 'cap' ? $this->faker->numberBetween(500, 2000) : null,
            'cleaning_fee' => $this->faker->randomFloat(2, 25, 100),
            'admin_fee' => $this->faker->randomFloat(2, 10, 50),
            'no_smoking' => $this->faker->boolean(80),
            'tracking_enabled' => $this->faker->boolean(90),
            'payment_day' => $this->faker->randomElement(RentalAgreement::PAYMENT_DAYS),
            'status' => $this->faker->randomElement(RentalAgreement::STATUSES),
        ];
    }
}
