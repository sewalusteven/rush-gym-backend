<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    use HasFactory;
    protected $fillable = ['name','duration','price'];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
