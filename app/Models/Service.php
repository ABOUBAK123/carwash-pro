<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'carwash_id', 'name', 'description', 'price', 'duration', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
