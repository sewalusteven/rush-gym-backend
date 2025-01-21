<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['amount','narration','type', 'category','transaction_date','payment_method_id'];

    public static function getDailyTransactions()
    {
        return DB::table('transactions')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as transactions'))
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->get();
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);

    }


    public static function getMonthlyTransactions()
    {
        return DB::table('transactions')
            ->select(DB::raw('MONTH(created_at) as month, YEAR(created_at) as year'), DB::raw('count(*) as transactions'))
            ->groupBy('month', 'year')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
    }

    public static function getBalance()
    {
        // Get the sum of credit and debit amounts
        $creditSum = DB::table('transactions')
            ->where('type', 'credit')
            ->sum('amount');

        $debitSum = DB::table('transactions')
            ->where('type', 'debit')
            ->sum('amount');

        $balance = $creditSum - $debitSum;

        return number_format($balance, 2); // Formats to 2 decimal places


    }
}
