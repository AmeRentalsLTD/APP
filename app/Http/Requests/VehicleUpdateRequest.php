<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleUpdateRequest extends FormRequest
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
        $vehicleId = $this->route('vehicle')->id ?? null;

        return [
            'registration' => [
                'sometimes',
                'string',
                'max:15',
                'regex:/^[A-Z0-9]+$/',
                Rule::unique('vehicles', 'registration')->ignore($vehicleId),
            ],
            'make' => ['sometimes', 'nullable', 'string', 'max:60'],
            'model' => ['sometimes', 'nullable', 'string', 'max:60'],
            'variant' => ['sometimes', 'nullable', 'string', 'max:60'],
            'year' => ['sometimes', 'nullable', 'integer', 'between:1900,' . (date('Y') + 1)],
            'mileage' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'mot_expiry' => ['sometimes', 'nullable', 'date'],
            'road_tax_due' => ['sometimes', 'nullable', 'date'],
            'purchase_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'monthly_finance' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'has_vat' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'string', Rule::in(Vehicle::STATUSES)],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
