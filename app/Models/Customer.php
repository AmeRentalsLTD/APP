<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    public const TYPES = [
        'individual',
        'company',
    ];

    protected $fillable = [
        'type',
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'postcode',
        'country',
        'driving_license_no',
        'dob',
        'nin',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    protected $appends = [
        'display_name',
    ];

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function rentalAgreements(): HasMany
    {
        return $this->hasMany(RentalAgreement::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Invoice::class);
    }

    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function activeRental(): HasOne
    {
        return $this->hasOne(RentalAgreement::class)->where('status', 'active');
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->type === 'company' && $this->company_name) {
            return (string) $this->company_name;
        }

        $name = trim(collect([$this->first_name, $this->last_name])->filter()->implode(' '));

        if ($name !== '') {
            return $name;
        }

        return (string) ($this->email ?? $this->phone ?? '');
    }

    public function getBillingAddressLinesAttribute(): array
    {
        return array_values(array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->postcode,
            $this->country,
        ], fn ($line) => filled($line)));
    }
}
