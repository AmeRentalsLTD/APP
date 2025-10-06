<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_id',
        'amount_net',
        'vat_rate',
        'held_at',
        'released_at',
        'status',
        'note',
    ];

    protected $casts = [
        'amount_net' => 'decimal:2',
        'vat_rate' => 'integer',
        'held_at' => 'date',
        'released_at' => 'date',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function canBeReleased(Carbon $date): bool
    {
        if ($this->status === 'released') {
            return false;
        }

        if (! $this->rental || ! $this->rental->end_date) {
            return false;
        }

        return $date->greaterThanOrEqualTo($this->rental->end_date->copy()->addDays(14));
    }
}
