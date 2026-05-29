<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    protected $fillable = [
        'smtp_host', 'smtp_port', 'smtp_user', 'smtp_password',
        'smtp_encryption', 'from_email', 'from_name', 'enable_notifications',
    ];

    protected $casts = [
        'enable_notifications' => 'boolean',
        'smtp_port'            => 'integer',
    ];

    public static function instance(): self
    {
        return static::firstOrCreate([], [
            'smtp_host'            => 'smtp.gmail.com',
            'smtp_port'            => 587,
            'smtp_encryption'      => 'tls',
            'from_name'            => 'CarWash Pro',
            'enable_notifications' => true,
        ]);
    }
}
