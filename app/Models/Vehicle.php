<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'registration', 'make', 'model', 'variant',
        'year', 'mileage',
        'mot_expiry', 'road_tax_due',
        'purchase_price', 'monthly_finance', 'has_vat',
        'status', 'notes',
    ];

    protected $casts = [
        'year'           => 'integer',
        'mileage'        => 'integer',
        'mot_expiry'     => 'date',
        'road_tax_due'   => 'date',
        'purchase_price' => 'decimal:2',
        'monthly_finance'=> 'decimal:2',
        'has_vat'        => 'boolean',
    ];
}
