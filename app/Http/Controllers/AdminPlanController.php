<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class AdminPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::ordered();
        return view('admin.plans', compact('plans'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:60',
            'description'       => 'nullable|string|max:200',
            'price_monthly_xof' => 'required|numeric|min:0',
            'price_monthly_eur' => 'required|numeric|min:0',
            'max_employees'     => 'required|integer|min:-1',
            'max_invoices'      => 'required|integer|min:-1',
            'trial_days'        => 'required|integer|min:0',
            'badge'             => 'nullable|string|max:30',
            'color'             => 'required|string|max:10',
            'is_active'         => 'boolean',
            'features'          => 'nullable|array',
            'features.*.label'  => 'required|string',
            'features.*.included' => 'required|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $plan->update($data);

        return back()->with('success', "Plan « {$plan->name} » mis à jour.");
    }

    public function toggleActive(Plan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);
        return back()->with('success', 'Statut du plan mis à jour.');
    }
}
