<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivePlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this['id'],
            'member' => [
                'id' => $this['member']['id'],
                'name' => $this['member']['name'],
                'email' => $this['member']['email'],
                'phone' => $this['member']['phone_number'],
            ],
            'membershipPlan' => [
                'id' => $this['membershipPlan']['id'],
                'name' => $this['membershipPlan']['name'],
                'duration' => $this['membershipPlan']['duration'],
                'price' => $this['membershipPlan']['price'],
            ],
            'totalAmount' => $this['total_amount'],
            'totalPaid' => $this['total_paid'],
            'startDate' => $this['start_date'],
            'endDate' => $this['end_date'],
            'isActive' => $this['is_active'],
        ];
    }

}
