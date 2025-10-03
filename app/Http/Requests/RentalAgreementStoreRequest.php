<?php

namespace App\Http\Requests;

use App\Models\RentalAgreement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RentalAgreementStoreRequest extends FormRequest
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
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'billing_cycle' => ['required', 'string', Rule::in(RentalAgreement::BILLING_CYCLES)],
            'rate_amount' => ['required', 'numeric', 'min:0'],
            'deposit_amount' => ['required', 'numeric', 'min:0'],
            'notice_days' => ['required', 'integer', 'between:0,365'],
            'deposit_release_days' => ['required', 'integer', 'between:0,365'],
            'insurance_option' => ['required', 'string', Rule::in(RentalAgreement::INSURANCE_OPTIONS)],
            'mileage_policy' => ['required', 'string', Rule::in(RentalAgreement::MILEAGE_POLICIES)],
            'mileage_cap' => Rule::when(
                fn () => $this->input('mileage_policy') === 'cap',
                ['required', 'integer', 'min:0'],
                ['nullable', 'integer', 'min:0']
            ),
            'cleaning_fee' => ['required', 'numeric', 'min:0'],
            'admin_fee' => ['required', 'numeric', 'min:0'],
            'no_smoking' => ['required', 'boolean'],
            'tracking_enabled' => ['required', 'boolean'],
            'payment_day' => ['required', 'string', Rule::in(RentalAgreement::PAYMENT_DAYS)],
            'status' => ['required', 'string', Rule::in(RentalAgreement::STATUSES)],
        ];
    }
}
