<?php

namespace App\Models;

use App\Services\VatCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'rental_id',
        'number',
        'issue_date',
        'due_date',
        'status',
        'subtotal_net',
        'tax',
        'total_gross',
        'currency',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal_net' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_gross' => 'decimal:2',
    ];

    protected $appends = [
        'balance',
    ];

    protected static function booted(): void
    {
        static::saving(function (Invoice $invoice) {
            $invoice->recalculateTotals();
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function recalculateTotals(): void
    {
        $net = $this->items->sum('line_total_net');
        $tax = $this->items->sum('line_tax');

        $this->subtotal_net = $net;
        $this->tax = $tax;
        $this->total_gross = $net + $tax;
    }

    public function syncStatus(): void
    {
        $paid = $this->payments()->sum('amount_gross');

        if ($paid <= 0) {
            return;
        }

        if ($paid >= $this->total_gross) {
            $this->status = 'paid';
        } elseif ($paid > 0) {
            $this->status = 'part_paid';
        }

        $this->save();
    }

    public function markSent(Carbon $date): void
    {
        $this->status = 'sent';
        $this->issue_date = $date;
        $this->due_date = $date->copy()->addDays(3);
        $this->save();
    }

    public function markOverdueIfNecessary(Carbon $date): void
    {
        if ($this->status === 'paid') {
            return;
        }

        if ($date->greaterThan($this->due_date)) {
            $this->status = 'overdue';
            $this->save();
        }
    }

    public function addItem(string $type, string $description, float $qty, float $unitNet, int $vatRate): InvoiceItem
    {
        $calculator = new VatCalculator($vatRate);
        $net = $calculator->netTotal($unitNet, $qty);
        $tax = $calculator->taxAmount($net);
        $gross = $net + $tax;

        return $this->items()->create([
            'type' => $type,
            'description' => $description,
            'qty' => $qty,
            'unit_price_net' => $unitNet,
            'vat_rate' => $vatRate,
            'line_total_net' => $net,
            'line_tax' => $tax,
            'line_total_gross' => $gross,
        ]);
    }

    public function getBalanceAttribute(): float
    {
        $paid = $this->payments->sum('amount_gross');

        return max((float) $this->total_gross - (float) $paid, 0);
    }

    public function updateTotals(): void
    {
        $this->recalculateTotals();
        $this->saveQuietly();
    }
}
