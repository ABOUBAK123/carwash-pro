<?php

namespace App\Http\Controllers;

use App\Models\Carwash;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function carwashes()
    {
        $carwashes = Carwash::with('manager')->orderBy('created_at', 'desc')->get();
        return view('admin.carwashes', compact('carwashes'));
    }

    public function toggleCarwash(Carwash $carwash)
    {
        $carwash->update(['is_active' => !$carwash->is_active]);
        $msg = $carwash->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Centre \"{$carwash->name}\" $msg.");
    }

    public function deleteCarwash(Carwash $carwash)
    {
        $carwash->delete();
        return back()->with('success', 'Centre supprimé.');
    }

    public function users()
    {
        $users = User::with('carwash')->orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function toggleUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Impossible de désactiver un administrateur.']);
        }
        $user->update(['is_active' => !$user->is_active]);
        $msg = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Utilisateur \"{$user->full_name}\" $msg.");
    }

    public function assignCarwash(Request $request, User $user)
    {
        $data = $request->validate([
            'carwash_id' => 'required|exists:carwashes,id',
        ]);
        $user->update($data);
        return back()->with('success', 'Centre assigné.');
    }

    public function createCarwash(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        Carwash::create($data);
        return back()->with('success', 'Centre de lavage créé.');
    }
}
