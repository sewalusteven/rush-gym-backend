<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeightRecord extends Model
{
    use HasFactory;
    protected $fillable = ['member_id','date','weight'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
