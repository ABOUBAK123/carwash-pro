<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;

class PricingController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::all();
        return view('pricing', compact('plans'));
    }
}
