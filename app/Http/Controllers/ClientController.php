<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $carwashId = Auth::user()->carwash_id;
        $clients = Client::where('carwash_id', $carwashId)->orderBy('created_at', 'desc')->get();
        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'vehicle_brand' => 'nullable|string|max:100',
            'vehicle_plate' => 'nullable|string|max:20',
        ]);

        $data['carwash_id'] = Auth::user()->carwash_id;
        Client::create($data);
        return back()->with('success', 'Client ajouté.');
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'vehicle_brand' => 'nullable|string|max:100',
            'vehicle_plate' => 'nullable|string|max:20',
        ]);

        $client->update($data);
        return back()->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Client supprimé.');
    }
}
