<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $carwash = $user->carwash;
        $plans   = Plan::ordered();
        $current = Plan::findBySlug($carwash->plan ?? 'trial');

        return view('subscription.index', compact('carwash', 'plans', 'current'));
    }

    public function upgrade(Request $request)
    {
        $slugs = Plan::paid()->pluck('slug')->toArray();
        $data  = $request->validate([
            'plan' => 'required|in:' . implode(',', $slugs),
        ]);

        $carwash = Auth::user()->carwash;
        $plan    = Plan::findBySlug($data['plan']);

        $carwash->update([
            'plan'                 => $plan->slug,
            'subscription_status'  => 'active',
            'subscription_ends_at' => now()->addDays(30),
        ]);

        // Enregistrer la commission si le centre a été parrainé
        if ($carwash->referred_by && $plan->price_monthly_xof > 0) {
            $commissionRate   = 3.00;
            $commissionAmount = round($plan->price_monthly_xof * $commissionRate / 100, 2);

            Commission::create([
                'commissionnaire_id'      => $carwash->referred_by,
                'carwash_id'              => $carwash->id,
                'plan_slug'               => $plan->slug,
                'subscription_amount_xof' => $plan->price_monthly_xof,
                'commission_amount_xof'   => $commissionAmount,
                'percentage'              => $commissionRate,
                'status'                  => 'pending',
            ]);
        }

        return back()->with('success', "Abonnement {$plan->name} activé jusqu'au " . now()->addDays(30)->format('d/m/Y') . '.');
    }
}
