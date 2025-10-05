<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ProfitAndLossRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('year')) {
            $year = (int) $this->input('year');

            $this->merge([
                'start_date' => $this->input('start_date') ?? Carbon::create($year, 1, 1)->toDateString(),
                'end_date' => $this->input('end_date') ?? Carbon::create($year, 12, 31)->toDateString(),
            ]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'year' => ['nullable', 'integer', 'between:2000,' . (date('Y') + 5)],
            'start_date' => ['required_without:year', 'date'],
            'end_date' => ['required_with:start_date', 'date', 'after_or_equal:start_date'],
        ];
    }
}
