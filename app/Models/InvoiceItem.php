<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'type',
        'description',
        'qty',
        'unit_price_net',
        'vat_rate',
        'line_total_net',
        'line_tax',
        'line_total_gross',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'unit_price_net' => 'decimal:2',
        'line_total_net' => 'decimal:2',
        'line_tax' => 'decimal:2',
        'line_total_gross' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saved(function (InvoiceItem $item) {
            $item->invoice?->updateTotals();
        });

        static::deleted(function (InvoiceItem $item) {
            $item->invoice?->updateTotals();
        });
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
