<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'commissionnaire_id', 'carwash_id', 'plan_slug',
        'subscription_amount_xof', 'commission_amount_xof',
        'percentage', 'status', 'paid_at',
    ];

    protected $casts = [
        'paid_at'                  => 'datetime',
        'commission_amount_xof'    => 'decimal:2',
        'subscription_amount_xof'  => 'decimal:2',
        'percentage'               => 'decimal:2',
    ];

    public function commissionnaire()
    {
        return $this->belongsTo(User::class, 'commissionnaire_id');
    }

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }
}
