<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\RentalAgreement */
class RentalAgreementResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'vehicle_id' => $this->vehicle_id,
            'customer_id' => $this->customer_id,
            'start_date' => optional($this->start_date)?->toDateString(),
            'end_date' => optional($this->end_date)?->toDateString(),
            'billing_cycle' => $this->billing_cycle,
            'rate_amount' => $this->rate_amount,
            'deposit_amount' => $this->deposit_amount,
            'notice_days' => $this->notice_days,
            'deposit_release_days' => $this->deposit_release_days,
            'insurance_option' => $this->insurance_option,
            'mileage_policy' => $this->mileage_policy,
            'mileage_cap' => $this->mileage_cap,
            'cleaning_fee' => $this->cleaning_fee,
            'admin_fee' => $this->admin_fee,
            'no_smoking' => $this->no_smoking,
            'tracking_enabled' => $this->tracking_enabled,
            'payment_day' => $this->payment_day,
            'status' => $this->status,
            'created_at' => optional($this->created_at)?->toAtomString(),
            'updated_at' => optional($this->updated_at)?->toAtomString(),
            'vehicle' => VehicleResource::make($this->whenLoaded('vehicle')),
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
        ];
    }
}
