<?php

namespace Tests\Feature\Console;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SyncVehicleComplianceCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_vehicle_compliance_using_gov_uk_apis(): void
    {
        config()->set('services.gov_uk.dvla.api_key', 'test-dvla-key');
        config()->set('services.gov_uk.dvsa.api_key', 'test-dvsa-key');
        config()->set('services.gov_uk.dvla.base_url', 'https://dvla.test');
        config()->set('services.gov_uk.dvsa.base_url', 'https://dvsa.test');

        Http::fake([
            'https://dvla.test/vehicles' => Http::response([
                'taxDueDate' => '2025-03-01',
            ], 200),
            'https://dvsa.test/mot-tests*' => Http::response([
                [
                    'motTestExpiryDate' => '2025-02-01',
                ],
            ], 200),
        ]);

        $vehicle = Vehicle::factory()->create([
            'registration' => 'AB12CDE',
            'mot_expiry' => null,
            'road_tax_due' => null,
        ]);

        $this->artisan('vehicles:sync-compliance')
            ->assertExitCode(0);

        $vehicle->refresh();

        $this->assertSame('2025-02-01', $vehicle->mot_expiry?->toDateString());
        $this->assertSame('2025-03-01', $vehicle->road_tax_due?->toDateString());
    }
}
