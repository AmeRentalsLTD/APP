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

    public const CATEGORY_GROUPS = [
        'income' => [
            'rental_income',
            'deposit_received',
            'damage_recharge',
            'insurance_recharge',
            'late_fee_income',
            'additional_mileage',
            'accessory_hire',
            'vehicle_sale',
            'other_income',
        ],
        'expense' => [
            'maintenance',
            'repairs',
            'tyres',
            'fuel',
            'insurance',
            'road_tax',
            'mot',
            'breakdown_cover',
            'vehicle_purchase',
            'vehicle_finance_payment',
            'vehicle_lease',
            'vehicle_registration',
            'deposit_refund',
            'valeting',
            'cleaning_supplies',
            'parking',
            'tolls',
            'fine',
            'wages',
            'subcontractor_costs',
            'marketing',
            'software',
            'office_rent',
            'utilities',
            'accountancy',
            'legal',
            'bank_charges',
            'interest',
            'training',
            'travel',
            'other_expense',
        ],
    ];

    public const CATEGORIES = [
        'rental_income',
        'deposit_received',
        'damage_recharge',
        'insurance_recharge',
        'late_fee_income',
        'additional_mileage',
        'accessory_hire',
        'vehicle_sale',
        'other_income',
        'maintenance',
        'repairs',
        'tyres',
        'fuel',
        'insurance',
        'road_tax',
        'mot',
        'breakdown_cover',
        'vehicle_purchase',
        'vehicle_finance_payment',
        'vehicle_lease',
        'vehicle_registration',
        'deposit_refund',
        'valeting',
        'cleaning_supplies',
        'parking',
        'tolls',
        'fine',
        'wages',
        'subcontractor_costs',
        'marketing',
        'software',
        'office_rent',
        'utilities',
        'accountancy',
        'legal',
        'bank_charges',
        'interest',
        'training',
        'travel',
        'other_expense',
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

    public static function categories(?string $type = null): array
    {
        if ($type !== null && array_key_exists($type, self::CATEGORY_GROUPS)) {
            return self::CATEGORY_GROUPS[$type];
        }

        $groups = array_values(self::CATEGORY_GROUPS);

        if ($groups === []) {
            return [];
        }

        return array_values(array_unique(array_merge(...$groups)));
    }

    public static function defaultCategory(?string $type = null): ?string
    {
        $type ??= self::TYPES[0];

        $categories = self::categories($type);

        return $categories[0] ?? null;
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
