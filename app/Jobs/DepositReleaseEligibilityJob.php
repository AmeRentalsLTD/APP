<?php

namespace App\Jobs;

use App\Models\Deposit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class DepositReleaseEligibilityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        $today = Carbon::today();

        Deposit::with('rental')
            ->whereIn('status', ['held', 'partially_released'])
            ->get()
            ->each(function (Deposit $deposit) use ($today) {
                if ($deposit->canBeReleased($today)) {
                    $deposit->status = 'released';
                    $deposit->released_at = $today;
                    $deposit->save();
                }
            });
    }
}
