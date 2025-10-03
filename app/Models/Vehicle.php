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
        'offroad',
        'retired',
    ];

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

    public function rentalAgreements(): HasMany
    {
        return $this->hasMany(RentalAgreement::class);
    }

    public function activeRental(): HasOne
    {
        return $this->hasOne(RentalAgreement::class)->where('status', 'active');
    }
}
