<?php

namespace Database\Seeders;

use App\Models\FinancialTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FinancialTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $year = (int) date('Y');

        $transactions = [
            [
                'type' => 'income',
                'category' => 'rental_income',
                'reference' => 'INV-' . $year . '-001',
                'amount' => 1850.00,
                'transaction_date' => Carbon::create($year, 1, 8)->toDateString(),
                'notes' => 'January corporate rental batch',
            ],
            [
                'type' => 'income',
                'category' => 'rental_income',
                'reference' => 'INV-' . $year . '-015',
                'amount' => 2125.50,
                'transaction_date' => Carbon::create($year, 3, 18)->toDateString(),
                'notes' => 'Q1 SME subscriptions',
            ],
            [
                'type' => 'income',
                'category' => 'deposit',
                'reference' => 'DEP-' . $year . '-042',
                'amount' => 650.00,
                'transaction_date' => Carbon::create($year, 4, 2)->toDateString(),
                'notes' => 'Damage waiver deposit',
            ],
            [
                'type' => 'expense',
                'category' => 'maintenance',
                'reference' => 'SUP-' . $year . '-109',
                'amount' => 420.75,
                'transaction_date' => Carbon::create($year, 2, 12)->toDateString(),
                'notes' => 'Fleet servicing and MOT checks',
            ],
            [
                'type' => 'expense',
                'category' => 'fuel',
                'reference' => 'FUL-' . $year . '-220',
                'amount' => 315.30,
                'transaction_date' => Carbon::create($year, 3, 5)->toDateString(),
                'notes' => 'Refuelling pool vehicles',
            ],
            [
                'type' => 'expense',
                'category' => 'insurance',
                'reference' => 'INS-' . $year . '-300',
                'amount' => 980.00,
                'transaction_date' => Carbon::create($year, 1, 20)->toDateString(),
                'notes' => 'Quarterly fleet insurance',
            ],
            [
                'type' => 'income',
                'category' => 'other',
                'reference' => 'MIS-' . $year . '-071',
                'amount' => 240.00,
                'transaction_date' => Carbon::create($year, 5, 3)->toDateString(),
                'notes' => 'Accessory upsell',
            ],
            [
                'type' => 'expense',
                'category' => 'maintenance',
                'reference' => 'SUP-' . ($year - 1) . '-505',
                'amount' => 560.00,
                'transaction_date' => Carbon::create($year - 1, 11, 16)->toDateString(),
                'notes' => 'Bodywork repairs',
            ],
            [
                'type' => 'income',
                'category' => 'rental_income',
                'reference' => 'INV-' . ($year - 1) . '-320',
                'amount' => 1750.00,
                'transaction_date' => Carbon::create($year - 1, 10, 7)->toDateString(),
                'notes' => 'Seasonal rental package',
            ],
        ];

        foreach ($transactions as $transaction) {
            FinancialTransaction::create($transaction);
        }
    }
}
