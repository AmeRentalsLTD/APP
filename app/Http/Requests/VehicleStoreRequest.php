<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('registration')) {
            $this->merge([
                'registration' => strtoupper((string) $this->input('registration')),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'registration' => ['required', 'string', 'max:15', 'regex:/^[A-Z0-9]+$/', 'unique:vehicles,registration'],
            'make' => ['nullable', 'string', 'max:60'],
            'model' => ['nullable', 'string', 'max:60'],
            'variant' => ['nullable', 'string', 'max:60'],
            'year' => ['nullable', 'integer', 'between:1900,' . (date('Y') + 1)],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'mot_expiry' => ['nullable', 'date'],
            'road_tax_due' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'monthly_finance' => ['nullable', 'numeric', 'min:0'],
            'has_vat' => ['required', 'boolean'],
            'status' => ['required', 'string', Rule::in(Vehicle::STATUSES)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
