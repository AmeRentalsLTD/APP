<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    use HasFactory;

    public const TYPES = [
        'income',
        'expense',
    ];

    public const CATEGORIES = [
        'rental_income',
        'deposit',
        'maintenance',
        'fuel',
        'insurance',
        'fine',
        'other',
    ];

    protected $fillable = [
        'type',
        'category',
        'reference',
        'amount',
        'transaction_date',
        'vehicle_id',
        'customer_id',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
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
