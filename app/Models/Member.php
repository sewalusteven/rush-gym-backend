<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function activePlans(){
        return $this->hasMany(ActivePlan::class);
    }

    public static function getDailyMembers()
    {
        return DB::table('members')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as members'))
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get();
    }


    public static function getMonthlyMembers()
    {
        return DB::table('members')
            ->select(DB::raw('MONTH(created_at) as month, YEAR(created_at) as year'), DB::raw('count(*) as members'))
            ->groupBy('month', 'year')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
    }

}
