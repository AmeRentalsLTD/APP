<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_crud_flow(): void
    {
        $payload = [
            'registration' => 'ab12cde',
            'make' => 'Ford',
            'model' => 'Transit',
            'variant' => 'L3H2',
            'year' => 2022,
            'mileage' => 15000,
            'mot_expiry' => '2025-06-01',
            'road_tax_due' => '2025-05-01',
            'purchase_price' => 25000,
            'monthly_finance' => 450.50,
            'has_vat' => true,
            'status' => 'available',
            'notes' => 'EU spec van',
        ];

        $response = $this->postJson('/api/v1/vehicles', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.registration', 'AB12CDE');

        $vehicleId = $response->json('data.id');

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicleId,
            'registration' => 'AB12CDE',
            'status' => 'available',
        ]);

        $updatePayload = [
            'mileage' => 18000,
            'status' => 'allocated',
        ];

        $this->patchJson("/api/v1/vehicles/{$vehicleId}", $updatePayload)
            ->assertOk()
            ->assertJsonPath('data.mileage', 18000)
            ->assertJsonPath('data.status', 'allocated');

        $this->deleteJson("/api/v1/vehicles/{$vehicleId}")
            ->assertNoContent();

        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicleId,
        ]);
    }
}
