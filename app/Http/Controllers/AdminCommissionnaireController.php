<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminCommissionnaireController extends Controller
{
    public function index()
    {
        $commissionnaires = User::where('role', 'commissionnaire')
            ->withCount('referredCarwashes')
            ->orderBy('created_at', 'desc')
            ->get();

        // Attacher les totaux de commissions
        $commissionnaires->each(function ($user) {
            $user->total_commission   = Commission::where('commissionnaire_id', $user->id)->sum('commission_amount_xof');
            $user->pending_commission = Commission::where('commissionnaire_id', $user->id)->where('status', 'pending')->sum('commission_amount_xof');
            $user->paid_commission    = Commission::where('commissionnaire_id', $user->id)->where('status', 'paid')->sum('commission_amount_xof');
            $user->commissions_list   = Commission::where('commissionnaire_id', $user->id)
                ->with('carwash')->orderBy('created_at', 'desc')->get();
        });

        $stats = [
            'total'           => $commissionnaires->count(),
            'active'          => $commissionnaires->where('is_active', true)->count(),
            'verified'        => $commissionnaires->where('identity_verified', true)->count(),
            'total_commission'=> Commission::sum('commission_amount_xof'),
            'pending'         => Commission::where('status', 'pending')->sum('commission_amount_xof'),
        ];

        return view('admin.commissionnaires', compact('commissionnaires', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'nullable|string|max:30',
            'password'          => 'required|string|min:8|confirmed',
            'identity_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('identity_document')) {
            $path = $request->file('identity_document')
                ->store('identity-documents', 'local');
        }

        User::create([
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'password'          => Hash::make($data['password']),
            'role'              => 'commissionnaire',
            'is_active'         => true,
            'identity_document' => $path,
            'identity_verified' => false,
            'currency'          => 'XOF',
            'language'          => 'fr',
        ]);

        return back()->with('success', 'Compte commissionnaire créé.');
    }

    public function toggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $state = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Compte {$state}.");
    }

    public function verify(User $user)
    {
        $user->update(['identity_verified' => !$user->identity_verified]);
        $state = $user->identity_verified ? 'vérifiée' : 'non vérifiée';
        return back()->with('success', "Pièce d'identité marquée comme {$state}.");
    }

    public function destroy(User $user)
    {
        if ($user->identity_document) {
            Storage::disk('local')->delete($user->identity_document);
        }
        $user->delete();
        return back()->with('success', 'Compte commissionnaire supprimé.');
    }

    public function downloadDocument(User $user)
    {
        if (!$user->identity_document || !Storage::disk('local')->exists($user->identity_document)) {
            return back()->with('error', 'Fichier introuvable.');
        }

        $ext      = pathinfo($user->identity_document, PATHINFO_EXTENSION);
        $filename = "identite_{$user->first_name}_{$user->last_name}.{$ext}";

        return Storage::disk('local')->download($user->identity_document, $filename);
    }

    public function markPaid(User $user)
    {
        Commission::where('commissionnaire_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'paid', 'paid_at' => now()]);

        return back()->with('success', 'Toutes les commissions en attente marquées comme payées.');
    }
}
