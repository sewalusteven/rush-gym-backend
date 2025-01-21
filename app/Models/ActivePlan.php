<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivePlan extends Model
{
    use HasFactory;
    protected $fillable = ['total_amount','total_paid','start_date','end_date', 'is_active', 'member_id','membership_plan_id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function member(){
        return $this->belongsTo(Member::class);
    }

    public function membershipPlan(){
        return $this->belongsTo(MembershipPlan::class);
    }
}
