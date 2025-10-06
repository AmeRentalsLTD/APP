<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'method',
        'amount_gross',
        'paid_at',
        'reference',
        'notes',
    ];

    protected $casts = [
        'amount_gross' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saved(function (Payment $payment) {
            $payment->invoice->syncStatus();
        });

        static::deleted(function (Payment $payment) {
            $payment->invoice->syncStatus();
        });
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
