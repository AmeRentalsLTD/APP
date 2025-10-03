<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    public const TYPES = [
        'individual',
        'sole_trader',
        'partnership',
        'ltd',
        'llp',
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

    public function rentalAgreements(): HasMany
    {
        return $this->hasMany(RentalAgreement::class);
    }
}
