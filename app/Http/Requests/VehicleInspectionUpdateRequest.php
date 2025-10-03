<?php

namespace App\Http\Requests;

use App\Models\VehicleInspection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleInspectionUpdateRequest extends FormRequest
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
            'vehicle_id' => ['sometimes', 'exists:vehicles,id'],
            'type' => ['sometimes', Rule::in(VehicleInspection::TYPES)],
            'inspected_at' => ['sometimes', 'date'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'front_image_path' => ['sometimes', 'string', 'max:255'],
            'rear_image_path' => ['sometimes', 'string', 'max:255'],
            'left_image_path' => ['sometimes', 'string', 'max:255'],
            'right_image_path' => ['sometimes', 'string', 'max:255'],
            'tyres_image_path' => ['sometimes', 'string', 'max:255'],
            'windscreen_image_path' => ['sometimes', 'string', 'max:255'],
            'mirrors_image_path' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
