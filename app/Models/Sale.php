<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ['amount','service_id','payment_method_id','narration'];

    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class);
    }
}
