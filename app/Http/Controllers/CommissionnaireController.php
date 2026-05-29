<?php

namespace App\Http\Controllers;

use App\Models\Carwash;
use App\Models\Commission;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CommissionnaireController extends Controller
{
    public function dashboard()
    {
        $user      = Auth::user();
        $carwashes = Carwash::where('referred_by', $user->id)
            ->with('manager')
            ->orderBy('created_at', 'desc')
            ->get();

        $commissions = Commission::where('commissionnaire_id', $user->id)
            ->with('carwash')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_centers'       => $carwashes->count(),
            'active_subscriptions'=> $carwashes->filter(fn($c) => !$c->subscriptionExpired())->count(),
            'total_commission'    => $commissions->sum('commission_amount_xof'),
            'pending_commission'  => $commissions->where('status', 'pending')->sum('commission_amount_xof'),
            'paid_commission'     => $commissions->where('status', 'paid')->sum('commission_amount_xof'),
        ];

        $plans = Plan::ordered();

        return view('commissionnaire.dashboard', compact('user', 'carwashes', 'commissions', 'stats', 'plans'));
    }

    public function createCenter(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:150',
            'address'    => 'required|string|max:255',
            'city'       => 'required|string|max:100',
            'phone'      => 'nullable|string|max:30',
            'email'      => 'nullable|email|max:150',
        ]);

        $carwash = Carwash::create(array_merge($data, [
            'is_active'           => true,
            'referred_by'         => Auth::id(),
            'plan'                => 'trial',
            'subscription_status' => 'trial',
            'trial_ends_at'       => now()->addDays(14),
        ]));

        return back()->with('success', "Centre « {$carwash->name} » créé. Essai gratuit de 14 jours activé.");
    }
}
