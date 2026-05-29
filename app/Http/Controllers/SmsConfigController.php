<?php

namespace App\Http\Controllers;

use App\Models\SmsConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsConfigController extends Controller
{
    public function index()
    {
        $config = SmsConfig::firstOrCreate(
            ['carwash_id' => Auth::user()->carwash_id],
            ['provider' => 'custom', 'sender_name' => 'CarWash', 'auto_send' => false]
        );

        return view('sms.config', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'provider'    => 'required|in:custom,twilio,nexmo,orange',
            'api_key'     => 'nullable|string|max:255',
            'sender_name' => 'required|string|max:11',
            'auto_send'   => 'boolean',
        ]);

        SmsConfig::updateOrCreate(
            ['carwash_id' => Auth::user()->carwash_id],
            [...$data, 'auto_send' => $request->has('auto_send')]
        );

        return back()->with('success', 'Configuration SMS mise à jour.');
    }
}
