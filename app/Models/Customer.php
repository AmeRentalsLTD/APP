<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'billing_address',
        'vat_number',
        'company_number',
        'notes',
    ];

    protected $appends = [
        'display_name',
    ];

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Invoice::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: (string) $this->email;
    }

    public function getBillingAddressLinesAttribute(): array
    {
        return preg_split('/\r?\n/', (string) $this->billing_address) ?: [];
    }
}
