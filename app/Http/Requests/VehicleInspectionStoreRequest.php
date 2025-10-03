<?php

namespace App\Http\Requests;

use App\Models\VehicleInspection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleInspectionStoreRequest extends FormRequest
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
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'type' => ['required', Rule::in(VehicleInspection::TYPES)],
            'inspected_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'front_image_path' => ['required', 'string', 'max:255'],
            'rear_image_path' => ['required', 'string', 'max:255'],
            'left_image_path' => ['required', 'string', 'max:255'],
            'right_image_path' => ['required', 'string', 'max:255'],
            'tyres_image_path' => ['required', 'string', 'max:255'],
            'windscreen_image_path' => ['required', 'string', 'max:255'],
            'mirrors_image_path' => ['required', 'string', 'max:255'],
        ];
    }
}
