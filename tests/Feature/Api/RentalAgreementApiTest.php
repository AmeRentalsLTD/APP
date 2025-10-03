<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalAgreementApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_rental_agreement_lifecycle(): void
    {
        $vehicle = Vehicle::factory()->create();
        $customer = Customer::factory()->create();

        $payload = [
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'start_date' => '2024-01-01',
            'billing_cycle' => 'weekly',
            'rate_amount' => 350,
            'deposit_amount' => 500,
            'notice_days' => 14,
            'deposit_release_days' => 14,
            'insurance_option' => 'company',
            'mileage_policy' => 'cap',
            'mileage_cap' => 1200,
            'cleaning_fee' => 40,
            'admin_fee' => 25,
            'no_smoking' => true,
            'tracking_enabled' => true,
            'payment_day' => 'friday',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/v1/rental-agreements', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.vehicle_id', $vehicle->id);

        $agreementId = $response->json('data.id');

        $this->patchJson("/api/v1/rental-agreements/{$agreementId}", [
            'status' => 'ended',
            'end_date' => '2024-03-01',
        ])->assertOk()
            ->assertJsonPath('data.status', 'ended')
            ->assertJsonPath('data.end_date', '2024-03-01');
    }
}
