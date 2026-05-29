<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyConfig;
use App\Models\LoyaltyVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    public function index()
    {
        $carwashId = Auth::user()->carwash_id;

        $config = LoyaltyConfig::firstOrCreate(
            ['carwash_id' => $carwashId],
            ['required_visits' => 10, 'discount_percentage' => 10, 'is_active' => true]
        );

        $visits = LoyaltyVisit::where('carwash_id', $carwashId)
            ->orderByDesc('visits_count')
            ->get();

        $eligible = $visits->filter(fn($v) => $v->visits_count >= $config->required_visits);

        return view('loyalty.index', compact('config', 'visits', 'eligible'));
    }

    public function configure(Request $request)
    {
        $data = $request->validate([
            'required_visits'    => 'required|integer|min:1|max:100',
            'discount_percentage'=> 'required|numeric|min:0|max:100',
            'is_active'          => 'boolean',
        ]);

        LoyaltyConfig::updateOrCreate(
            ['carwash_id' => Auth::user()->carwash_id],
            [...$data, 'is_active' => $request->has('is_active')]
        );

        return back()->with('success', 'Programme de fidélité mis à jour.');
    }

    public function resetVisit(LoyaltyVisit $visit)
    {
        $visit->update(['visits_count' => 0, 'last_visit_at' => null]);
        return back()->with('success', 'Compteur réinitialisé pour ' . $visit->vehicle_plate);
    }
}
