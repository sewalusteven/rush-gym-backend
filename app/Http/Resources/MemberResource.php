<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sales = $this['sales']->map(function ($sale) {
            return [
              'amount' => $sale['amount'],
              'service' => $sale['service']['service'],
              'paymentMethod' => $sale['paymentMethod']['method'],
              'date' => $sale['sale_date'],
            ];
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone_number,
            'createdAt' => $this->created_at,
            'weightRecords' => $this->weightRecords,
            'sales' => $sales,
        ];
    }
}
