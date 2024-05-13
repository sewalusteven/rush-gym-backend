<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id' => $this->id,
          'amount' => $this->amount,
          'serviceId' => $this->service_id,
          'paymentMethodId' => $this->payment_method_id,
          'createdAt' => $this->created_at,
          'narration' => $this->narration,
          'service' => $this->service,
          'paymentMethod' => $this->paymentMethod,
          'member' => $this->member,
          'transaction' => $this->transaction
        ];
    }
}
