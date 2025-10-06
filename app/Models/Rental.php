<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'price_net',
        'vat_rate',
        'frequency',
        'deposit_net',
        'start_date',
        'end_date',
        'notice_days',
        'status',
    ];

    protected $casts = [
        'price_net' => 'decimal:2',
        'deposit_net' => 'decimal:2',
        'vat_rate' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function deposit(): HasOne
    {
        return $this->hasOne(Deposit::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function dueToday(Carbon $date): bool
    {
        if ($this->frequency === 'weekly') {
            $targetDay = (int) config('finance.weekly_invoice_day', 5);

            return $date->dayOfWeekIso === $targetDay;
        }

        $startDay = $this->start_date->day;
        $targetDay = min($startDay, $date->daysInMonth);

        return $date->day === $targetDay;
    }
}
