<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use App\Support\VehicleComplianceSyncer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SyncVehicleCompliance extends Command
{
    protected $signature = 'vehicles:sync-compliance {--registration= : Limit the sync to a specific registration}';

    protected $description = 'Sync road tax and MOT data from GOV.UK services for fleet vehicles';

    public function __construct(private readonly VehicleComplianceSyncer $syncer)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $query = Vehicle::query()->orderBy('id');

        $registration = $this->option('registration');

        if (is_string($registration) && trim($registration) !== '') {
            $query->where('registration', strtoupper(str_replace(' ', '', $registration)));
        }

        $total = 0;
        $updated = 0;

        $query->chunkById(50, function (Collection $vehicles) use (&$total, &$updated): void {
            foreach ($vehicles as $vehicle) {
                $total++;

                if ($this->syncer->sync($vehicle)) {
                    $updated++;
                }
            }
        });

        if ($total === 0) {
            $this->warn('No vehicles matched the provided filters.');

            return self::SUCCESS;
        }

        $this->info(sprintf('Synced compliance for %d vehicle%s (%d updated).', $total, $total === 1 ? '' : 's', $updated));

        return self::SUCCESS;
    }
}
