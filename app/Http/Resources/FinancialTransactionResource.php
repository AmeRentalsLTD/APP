<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\FinancialTransaction */
class FinancialTransactionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $customer = $this->whenLoaded('customer');
        $vehicle = $this->whenLoaded('vehicle');

        return [
            'id' => $this->id,
            'type' => $this->type,
            'category' => $this->category,
            'reference' => $this->reference,
            'amount' => (float) $this->amount,
            'transaction_date' => optional($this->transaction_date)?->toDateString(),
            'vehicle_id' => $this->vehicle_id,
            'vehicle' => $vehicle ? [
                'id' => $vehicle->id,
                'registration' => $vehicle->registration,
            ] : null,
            'customer_id' => $this->customer_id,
            'customer' => $customer ? [
                'id' => $customer->id,
                'display_name' => $this->formatCustomerName($customer),
            ] : null,
            'notes' => $this->notes,
            'created_at' => optional($this->created_at)?->toAtomString(),
            'updated_at' => optional($this->updated_at)?->toAtomString(),
        ];
    }

    protected function formatCustomerName($customer): string
    {
        if (! empty($customer->company_name)) {
            return (string) $customer->company_name;
        }

        $name = trim(sprintf('%s %s', (string) $customer->first_name, (string) $customer->last_name));

        return $name !== '' ? $name : 'Customer #' . $customer->id;
    }
}
