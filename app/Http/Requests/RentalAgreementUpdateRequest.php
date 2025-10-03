<?php

namespace App\Http\Requests;

use App\Models\RentalAgreement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RentalAgreementUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $fieldsToLower = ['billing_cycle', 'insurance_option', 'mileage_policy', 'payment_day', 'status'];

        foreach ($fieldsToLower as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => strtolower((string) $this->input($field)),
                ]);
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['sometimes', 'exists:vehicles,id'],
            'customer_id' => ['sometimes', 'exists:customers,id'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
            'billing_cycle' => ['sometimes', 'string', Rule::in(RentalAgreement::BILLING_CYCLES)],
            'rate_amount' => ['sometimes', 'numeric', 'min:0'],
            'deposit_amount' => ['sometimes', 'numeric', 'min:0'],
            'notice_days' => ['sometimes', 'integer', 'between:0,365'],
            'deposit_release_days' => ['sometimes', 'integer', 'between:0,365'],
            'insurance_option' => ['sometimes', 'string', Rule::in(RentalAgreement::INSURANCE_OPTIONS)],
            'mileage_policy' => ['sometimes', 'string', Rule::in(RentalAgreement::MILEAGE_POLICIES)],
            'mileage_cap' => Rule::when(
                fn () => $this->input('mileage_policy') === 'cap',
                ['required', 'integer', 'min:0'],
                ['nullable', 'integer', 'min:0']
            ),
            'cleaning_fee' => ['sometimes', 'numeric', 'min:0'],
            'admin_fee' => ['sometimes', 'numeric', 'min:0'],
            'no_smoking' => ['sometimes', 'boolean'],
            'tracking_enabled' => ['sometimes', 'boolean'],
            'payment_day' => ['sometimes', 'string', Rule::in(RentalAgreement::PAYMENT_DAYS)],
            'status' => ['sometimes', 'string', Rule::in(RentalAgreement::STATUSES)],
        ];
    }
}
