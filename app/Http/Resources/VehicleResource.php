<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Vehicle */
class VehicleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'registration' => $this->registration,
            'make' => $this->make,
            'model' => $this->model,
            'variant' => $this->variant,
            'year' => $this->year,
            'mileage' => $this->mileage,
            'mot_expiry' => optional($this->mot_expiry)?->toDateString(),
            'road_tax_due' => optional($this->road_tax_due)?->toDateString(),
            'purchase_price' => $this->purchase_price,
            'monthly_finance' => $this->monthly_finance,
            'has_vat' => $this->has_vat,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => optional($this->created_at)?->toAtomString(),
            'updated_at' => optional($this->updated_at)?->toAtomString(),
        ];
    }
}
