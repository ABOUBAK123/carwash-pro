<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsNotification extends Model
{
    protected $fillable = [
        'phone_number', 'message', 'type', 'status', 'sent_at',
    ];

    protected $casts = ['sent_at' => 'datetime'];
}
