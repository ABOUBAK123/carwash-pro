<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'carwash_id', 'name', 'phone', 'email', 'vehicle_brand', 'vehicle_plate',
    ];

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'client_phone', 'phone');
    }
}
