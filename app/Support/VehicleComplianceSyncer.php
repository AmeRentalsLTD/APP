<?php

namespace App\Support;

use App\Models\Vehicle;
use Carbon\CarbonInterface;

class VehicleComplianceSyncer
{
    public function __construct(private readonly GovUkVehicleComplianceClient $client)
    {
    }

    public function sync(Vehicle $vehicle): bool
    {
        $registration = (string) $vehicle->registration;

        if (trim($registration) === '') {
            return false;
        }

        $data = $this->client->fetch($registration);

        $changes = [];

        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            $current = $this->resolveCurrentValue($vehicle, $key);

            if ($current === $value) {
                continue;
            }

            $changes[$key] = $value;
        }

        if ($changes === []) {
            return false;
        }

        $vehicle->fill($changes);
        $vehicle->save();

        return true;
    }

    private function resolveCurrentValue(Vehicle $vehicle, string $attribute): ?string
    {
        $value = $vehicle->getAttribute($attribute);

        if ($value instanceof CarbonInterface) {
            return $value->toDateString();
        }

        if (is_string($value) && trim($value) !== '') {
            return $value;
        }

        return null;
    }
}
