<?php

namespace App\Console\Commands;

use Database\Seeders\FinanceDemoSeeder;
use Illuminate\Console\Command;

class FinanceDemoCommand extends Command
{
    protected $signature = 'finance:demo';

    protected $description = 'Seed finance demo data for AME Rentals Ltd';

    public function handle(): int
    {
        $this->call('db:seed', ['--class' => FinanceDemoSeeder::class]);

        $this->info('Demo data seeded for AME Rentals Ltd.');
        $this->line('Filament admin: /admin');
        $this->line('Reports available under Finance reports menu.');

        return self::SUCCESS;
    }
}
