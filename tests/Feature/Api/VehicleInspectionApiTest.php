<?php

namespace Tests\Feature\Api;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleInspectionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_inspection_crud_flow(): void
    {
        $vehicle = Vehicle::factory()->create();

        $payload = [
            'vehicle_id' => $vehicle->id,
            'type' => 'onhire',
            'inspected_at' => '2025-02-01',
            'notes' => 'On-hire inspection for new contract',
            'front_image_path' => 'vehicle-inspections/front/front.jpg',
            'rear_image_path' => 'vehicle-inspections/rear/rear.jpg',
            'left_image_path' => 'vehicle-inspections/left/left.jpg',
            'right_image_path' => 'vehicle-inspections/right/right.jpg',
            'tyres_image_path' => 'vehicle-inspections/tyres/tyres.jpg',
            'windscreen_image_path' => 'vehicle-inspections/windscreen/windscreen.jpg',
            'mirrors_image_path' => 'vehicle-inspections/mirrors/mirrors.jpg',
        ];

        $response = $this->postJson('/api/v1/vehicle-inspections', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.type', 'onhire')
            ->assertJsonPath('data.vehicle_id', $vehicle->id);

        $inspectionId = $response->json('data.id');

        $this->assertDatabaseHas('vehicle_inspections', [
            'id' => $inspectionId,
            'vehicle_id' => $vehicle->id,
            'type' => 'onhire',
        ]);

        $updatePayload = [
            'type' => 'weekly',
            'notes' => 'Weekly inspection captured',
            'mirrors_image_path' => 'vehicle-inspections/mirrors/updated.jpg',
        ];

        $this->patchJson("/api/v1/vehicle-inspections/{$inspectionId}", $updatePayload)
            ->assertOk()
            ->assertJsonPath('data.type', 'weekly')
            ->assertJsonPath('data.notes', 'Weekly inspection captured')
            ->assertJsonPath('data.mirrors_image_path', 'vehicle-inspections/mirrors/updated.jpg');

        $this->deleteJson("/api/v1/vehicle-inspections/{$inspectionId}")
            ->assertNoContent();

        $this->assertDatabaseMissing('vehicle_inspections', [
            'id' => $inspectionId,
        ]);
    }
}
