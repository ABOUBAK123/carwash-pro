<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $settings = PaymentSetting::instance();
        return view('admin.payment-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'stripe_public_key'     => 'nullable|string|max:255',
            'stripe_secret_key'     => 'nullable|string|max:255',
            'paypal_client_id'      => 'nullable|string|max:255',
            'merchant_account'      => 'nullable|string|max:255',
            'webhook_url'           => 'nullable|url|max:255',
            'monthly_price'         => 'required|numeric|min:0',
            'yearly_price'          => 'required|numeric|min:0',
            'currency'              => 'required|string|max:5',
            'enable_mobile_payment' => 'boolean',
            'orange_money_api_key'  => 'nullable|string|max:255',
            'mtn_momo_api_key'      => 'nullable|string|max:255',
            'moov_money_api_key'    => 'nullable|string|max:255',
            'wave_api_key'          => 'nullable|string|max:255',
        ]);

        $data['enable_mobile_payment'] = $request->boolean('enable_mobile_payment');

        PaymentSetting::instance()->update($data);

        return back()->with('success', 'Paramètres de paiement mis à jour.');
    }
}
