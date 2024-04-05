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
    public function weightRecords()
    {
        return $this->hasMany(WeightRecord::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
