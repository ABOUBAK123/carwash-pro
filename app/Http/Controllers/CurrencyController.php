<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('code')->get();
        return view('admin.currencies', compact('currencies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'   => 'required|string|max:5|unique:currencies,code',
            'name'   => 'required|string|max:80',
            'symbol' => 'required|string|max:10',
            'rate'   => 'required|numeric|min:0.000001',
        ]);

        Currency::create(array_merge($data, ['is_active' => true]));

        return back()->with('success', 'Devise ajoutée.');
    }

    public function update(Request $request, Currency $currency)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:80',
            'symbol'    => 'required|string|max:10',
            'rate'      => 'required|numeric|min:0.000001',
            'is_active' => 'boolean',
        ]);

        $currency->update($data);
        return back()->with('success', 'Devise mise à jour.');
    }

    public function toggle(Currency $currency)
    {
        $currency->update(['is_active' => !$currency->is_active]);
        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();
        return back()->with('success', 'Devise supprimée.');
    }
}
