<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ['amount','service_id','payment_method_id','narration','member_id','transaction_id','sale_date'];

    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }
    public function member(){
        return $this->belongsTo(Member::class);
    }

    // Get Daily Sales
    public static function getDailySales()
    {
        return DB::table('sales')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get();
    }

    // Get Monthly Sales
    public static function getMonthlySales()
    {
        return DB::table('sales')
            ->select(DB::raw('MONTH(created_at) as month, YEAR(created_at) as year'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('month', 'year')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
    }
}
