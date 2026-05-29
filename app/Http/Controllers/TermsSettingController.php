<?php

namespace App\Http\Controllers;

use App\Models\TermsSetting;
use Illuminate\Http\Request;

class TermsSettingController extends Controller
{
    public function index()
    {
        $terms = TermsSetting::instance();
        return view('admin.terms-settings', compact('terms'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
        ]);

        TermsSetting::instance()->update($data);

        return back()->with('success', 'Conditions générales mises à jour.');
    }
}
