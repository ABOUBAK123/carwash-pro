<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $carwashId = Auth::user()->carwash_id;
        $services = Service::where('carwash_id', $carwashId)->orderBy('created_at', 'desc')->get();
        return view('services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        $data['carwash_id'] = Auth::user()->carwash_id;
        Service::create($data);
        return back()->with('success', 'Service créé.');
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        $service->update($data);
        return back()->with('success', 'Service mis à jour.');
    }

    public function toggle(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        return back()->with('success', 'Statut du service modifié.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service supprimé.');
    }
}
