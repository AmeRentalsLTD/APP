<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'category',
        'vendor',
        'date',
        'net',
        'vat_rate',
        'tax',
        'gross',
        'reference',
        'attachment_path',
    ];

    protected $casts = [
        'date' => 'date',
        'net' => 'decimal:2',
        'tax' => 'decimal:2',
        'gross' => 'decimal:2',
        'vat_rate' => 'integer',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
