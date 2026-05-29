<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyConfig extends Model
{
    protected $fillable = [
        'carwash_id', 'required_visits', 'discount_percentage', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_percentage' => 'decimal:2',
    ];

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }
}
