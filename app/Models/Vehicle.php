<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    public const STATUSES = [
        'available',
        'allocated',
        'maintenance',
        'reserved',
        'offroad',
        'retired',
    ];

    public const COMPLIANCE_ALERT_WINDOW_DAYS = 30;

    protected $fillable = [
        'registration',
        'make',
        'model',
        'variant',
        'year',
        'mileage',
        'mot_expiry',
        'road_tax_due',
        'purchase_price',
        'monthly_finance',
        'has_vat',
        'status',
        'notes',
    ];

    protected $casts = [
        'year' => 'integer',
        'mileage' => 'integer',
        'mot_expiry' => 'date',
        'road_tax_due' => 'date',
        'purchase_price' => 'decimal:2',
        'monthly_finance' => 'decimal:2',
        'has_vat' => 'boolean',
    ];

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function activeRental(): HasOne
    {
        return $this->hasOne(Rental::class)->where('status', 'active');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(VehicleInspection::class);
    }
}
