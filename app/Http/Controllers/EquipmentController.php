<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function index()
    {
        $carwashId = Auth::user()->carwash_id;
        $equipment = Equipment::where('carwash_id', $carwashId)->orderBy('name')->get();

        $stats = [
            'total'       => $equipment->count(),
            'available'   => $equipment->where('status', 'available')->count(),
            'maintenance' => $equipment->where('status', 'maintenance')->count(),
            'broken'      => $equipment->where('status', 'broken')->count(),
            'total_cost'  => $equipment->sum('cost'),
        ];

        return view('equipment.index', compact('equipment', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:150',
            'type'          => 'required|in:washing_machine,vacuum,compressor,pressure_washer,other',
            'purchase_date' => 'nullable|date',
            'cost'          => 'nullable|numeric|min:0',
            'status'        => 'required|in:available,maintenance,broken',
            'notes'         => 'nullable|string|max:500',
        ]);

        Equipment::create([...$data, 'carwash_id' => Auth::user()->carwash_id]);

        return back()->with('success', 'Équipement ajouté.');
    }

    public function updateStatus(Request $request, Equipment $equipment)
    {
        $request->validate(['status' => 'required|in:available,maintenance,broken']);
        $equipment->update(['status' => $request->status]);
        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return back()->with('success', 'Équipement supprimé.');
    }
}
