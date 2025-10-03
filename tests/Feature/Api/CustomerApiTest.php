<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_creation_and_update(): void
    {
        $payload = [
            'type' => 'sole_trader',
            'company_name' => 'LogiRent LTD',
            'email' => 'ops@example.test',
            'phone' => '+44 1234 567890',
            'address_line1' => '10 Downing Street',
            'city' => 'London',
            'postcode' => 'SW1A 2AA',
        ];

        $response = $this->postJson('/api/v1/customers', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.type', 'sole_trader');

        $customerId = $response->json('data.id');

        $this->assertDatabaseHas('customers', [
            'id' => $customerId,
            'email' => 'ops@example.test',
        ]);

        $updatePayload = [
            'phone' => '+44 2030 123456',
            'city' => 'Manchester',
        ];

        $this->patchJson("/api/v1/customers/{$customerId}", $updatePayload)
            ->assertOk()
            ->assertJsonPath('data.city', 'Manchester');
    }
}
