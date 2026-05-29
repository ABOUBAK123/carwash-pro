<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsConfig extends Model
{
    protected $fillable = [
        'carwash_id', 'provider', 'api_key', 'sender_name', 'auto_send',
    ];

    protected $casts = [
        'auto_send' => 'boolean',
    ];

    public function carwash()
    {
        return $this->belongsTo(Carwash::class);
    }
}
