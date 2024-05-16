<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['amount','narration','user_id','transaction_id'];

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function getDailyExpenses()
    {
        return DB::table('expenses')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get();
    }

    // Get Monthly Sales
    public static function getMonthlyExpenses()
    {
        return DB::table('expenses')
            ->select(DB::raw('MONTH(created_at) as month, YEAR(created_at) as year'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('month', 'year')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
    }
}
