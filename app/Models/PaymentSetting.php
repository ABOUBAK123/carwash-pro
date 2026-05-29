<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'stripe_public_key', 'stripe_secret_key', 'paypal_client_id',
        'merchant_account', 'webhook_url',
        'monthly_price', 'yearly_price', 'currency',
        'enable_mobile_payment',
        'orange_money_api_key', 'mtn_momo_api_key', 'moov_money_api_key', 'wave_api_key',
    ];

    protected $casts = [
        'monthly_price'         => 'decimal:2',
        'yearly_price'          => 'decimal:2',
        'enable_mobile_payment' => 'boolean',
    ];

    public static function instance(): self
    {
        return static::firstOrCreate([], [
            'monthly_price' => 29.99,
            'yearly_price'  => 299.99,
            'currency'      => 'EUR',
            'enable_mobile_payment' => true,
        ]);
    }
}
