<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyVisit extends Model
{
    protected $fillable = [
        'carwash_id', 'vehicle_plate', 'client_name', 'client_phone',
        'visits_count', 'last_visit_at',
    ];

    protected $casts = [
        'last_visit_at' => 'datetime',
    ];

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }
}
