<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\VehicleInspection */
class VehicleInspectionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicle_id,
            'type' => $this->type,
            'inspected_at' => optional($this->inspected_at)?->toDateString(),
            'notes' => $this->notes,
            'vehicle' => $this->whenLoaded('vehicle', fn (): array => [
                'id' => $this->vehicle?->id,
                'registration' => $this->vehicle?->registration,
            ]),
            'front_image_path' => $this->front_image_path,
            'rear_image_path' => $this->rear_image_path,
            'left_image_path' => $this->left_image_path,
            'right_image_path' => $this->right_image_path,
            'tyres_image_path' => $this->tyres_image_path,
            'windscreen_image_path' => $this->windscreen_image_path,
            'mirrors_image_path' => $this->mirrors_image_path,
            'created_at' => optional($this->created_at)?->toAtomString(),
            'updated_at' => optional($this->updated_at)?->toAtomString(),
        ];
    }
}
