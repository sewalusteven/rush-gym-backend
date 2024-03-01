<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'membership_plan_id',
        'start_date',
        'end_date',
    ];

    public function membershipPlan()
    {
        return $this->belongsTo(MembershipPlan::class);
    }
}
