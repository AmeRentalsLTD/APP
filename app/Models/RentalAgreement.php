<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalAgreement extends Model
{
    use HasFactory;

    public const STATUSES = [
        'draft',
        'active',
        'paused',
        'ended',
    ];

    public const BILLING_CYCLES = ['weekly', 'monthly'];
    public const INSURANCE_OPTIONS = ['company', 'own'];
    public const MILEAGE_POLICIES = ['unlimited', 'cap'];
    public const PAYMENT_DAYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

    protected $fillable = [
        'vehicle_id',
        'customer_id',
        'start_date',
        'end_date',
        'billing_cycle',
        'rate_amount',
        'deposit_amount',
        'notice_days',
        'deposit_release_days',
        'insurance_option',
        'mileage_policy',
        'mileage_cap',
        'cleaning_fee',
        'admin_fee',
        'no_smoking',
        'tracking_enabled',
        'payment_day',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'rate_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'notice_days' => 'integer',
        'deposit_release_days' => 'integer',
        'mileage_cap' => 'integer',
        'no_smoking' => 'boolean',
        'tracking_enabled' => 'boolean',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
