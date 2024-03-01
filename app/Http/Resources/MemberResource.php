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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone_number,
            'planId' => $this->membership_plan_id,
            'start' => $this->start_date,
            'end' => $this->end_date,
            'createdAt' => $this->created_at,
            'membershipPlan' => $this->membershipPlan
        ];
    }
}
