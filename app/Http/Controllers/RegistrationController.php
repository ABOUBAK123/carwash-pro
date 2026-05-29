<?php

namespace App\Http\Controllers;

use App\Models\RegistrationRequest;
use App\Models\Carwash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = RegistrationRequest::orderBy('created_at', 'desc')->get();

        $counts = [
            'total'    => $registrations->count(),
            'pending'  => $registrations->where('status', 'pending')->count(),
            'approved' => $registrations->where('status', 'approved')->count(),
            'rejected' => $registrations->where('status', 'rejected')->count(),
        ];

        return view('admin.registrations', compact('registrations', 'counts'));
    }

    public function approve(RegistrationRequest $registration)
    {
        if (!$registration->isPending()) {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $carwash = Carwash::create([
            'name'       => $registration->center_name,
            'address'    => $registration->address,
            'city'       => $registration->city,
            'phone'      => $registration->phone,
            'email'      => $registration->email,
            'latitude'   => $registration->latitude,
            'longitude'  => $registration->longitude,
            'is_active'  => true,
        ]);

        $tempPassword = Str::random(12);

        $manager = User::create([
            'first_name' => explode(' ', $registration->owner_name)[0] ?? $registration->owner_name,
            'last_name'  => implode(' ', array_slice(explode(' ', $registration->owner_name), 1)) ?: '-',
            'email'      => $registration->email,
            'password'   => Hash::make($tempPassword),
            'role'       => 'manager',
            'carwash_id' => $carwash->id,
            'is_active'  => true,
            'currency'   => 'EUR',
            'language'   => 'fr',
        ]);

        $carwash->update(['manager_id' => $manager->id]);

        $registration->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', "Centre approuvé. Compte manager créé : {$registration->email} / {$tempPassword}");
    }

    public function reject(RegistrationRequest $registration)
    {
        if (!$registration->isPending()) {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $registration->update([
            'status'      => 'rejected',
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Demande rejetée.');
    }

    public function destroy(RegistrationRequest $registration)
    {
        $registration->delete();
        return back()->with('success', 'Demande supprimée.');
    }
}
