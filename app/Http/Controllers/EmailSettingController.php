<?php

namespace App\Http\Controllers;

use App\Models\EmailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class EmailSettingController extends Controller
{
    public function index()
    {
        $settings = EmailSetting::instance();
        return view('admin.email-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'smtp_host'            => 'required|string|max:255',
            'smtp_port'            => 'required|integer|min:1|max:65535',
            'smtp_user'            => 'nullable|string|max:255',
            'smtp_password'        => 'nullable|string|max:255',
            'smtp_encryption'      => 'required|in:tls,ssl,none',
            'from_email'           => 'nullable|email|max:255',
            'from_name'            => 'required|string|max:100',
            'enable_notifications' => 'boolean',
        ]);

        $data['enable_notifications'] = $request->boolean('enable_notifications');

        // Don't overwrite password if left blank
        if (empty($data['smtp_password'])) {
            unset($data['smtp_password']);
        }

        EmailSetting::instance()->update($data);

        return back()->with('success', 'Paramètres email mis à jour.');
    }

    public function test(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);

        $settings = EmailSetting::instance();

        try {
            Config::set('mail.mailers.smtp.host',       $settings->smtp_host);
            Config::set('mail.mailers.smtp.port',       $settings->smtp_port);
            Config::set('mail.mailers.smtp.username',   $settings->smtp_user);
            Config::set('mail.mailers.smtp.password',   $settings->smtp_password);
            Config::set('mail.mailers.smtp.encryption', $settings->smtp_encryption === 'none' ? null : $settings->smtp_encryption);
            Config::set('mail.from.address', $settings->from_email ?? $settings->smtp_user);
            Config::set('mail.from.name',    $settings->from_name);

            Mail::raw('Ceci est un email de test envoyé depuis CarWash Pro.', function ($msg) use ($request, $settings) {
                $msg->to($request->test_email)
                    ->subject('Test email — CarWash Pro')
                    ->from($settings->from_email ?? $settings->smtp_user, $settings->from_name);
            });

            return back()->with('success', "Email de test envoyé à {$request->test_email}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Échec envoi : ' . $e->getMessage());
        }
    }
}
