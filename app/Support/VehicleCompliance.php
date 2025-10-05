<?php

namespace App\Support;

use App\Models\Vehicle;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class VehicleCompliance
{
    public static function status(null|string|CarbonInterface $value): string
    {
        $date = self::resolveDate($value);

        if (! $date) {
            return 'missing';
        }

        $today = Carbon::today();

        if ($date->lt($today)) {
            return 'expired';
        }

        if ($date->lte($today->copy()->addDays(Vehicle::COMPLIANCE_ALERT_WINDOW_DAYS))) {
            return 'due_soon';
        }

        return 'compliant';
    }

    public static function color(null|string|CarbonInterface $value): string
    {
        return match (self::status($value)) {
            'expired' => 'danger',
            'due_soon' => 'warning',
            'compliant' => 'success',
            default => 'gray',
        };
    }

    public static function label(null|string|CarbonInterface $value): string
    {
        $date = self::resolveDate($value);

        if (! $date) {
            return 'Not set';
        }

        return match (self::status($date)) {
            'expired' => 'Expired · ' . $date->toFormattedDateString(),
            'due_soon' => 'Due soon · ' . $date->toFormattedDateString(),
            'compliant' => 'Compliant · ' . $date->toFormattedDateString(),
            default => 'Not set',
        };
    }

    public static function resolveDate(null|string|CarbonInterface $value): ?CarbonInterface
    {
        if ($value instanceof CarbonInterface) {
            return $value;
        }

        if (! $value) {
            return null;
        }

        return Carbon::parse($value);
    }
}
