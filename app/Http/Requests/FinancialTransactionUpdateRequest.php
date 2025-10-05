<?php

namespace App\Http\Requests;

use App\Models\FinancialTransaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinancialTransactionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', Rule::in(FinancialTransaction::TYPES)],
            'category' => ['sometimes', 'string', Rule::in(FinancialTransaction::CATEGORIES)],
            'reference' => ['sometimes', 'nullable', 'string', 'max:120'],
            'amount' => ['sometimes', 'numeric', 'min:0'],
            'transaction_date' => ['sometimes', 'date'],
            'vehicle_id' => ['sometimes', 'nullable', 'integer', 'exists:vehicles,id'],
            'customer_id' => ['sometimes', 'nullable', 'integer', 'exists:customers,id'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
