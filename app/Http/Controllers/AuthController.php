<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Carwash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if (!$user->is_active && !$user->isAdmin()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Votre compte est inactif. Contactez l\'administrateur.']);
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Email ou mot de passe incorrect.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,manager,receptionist,employee',
            'currency' => 'nullable|in:EUR,USD,GBP,XOF,MAD',
            'language' => 'nullable|in:fr,en,es,ar',
        ]);

        $isAdmin = !User::where('role', 'admin')->exists();

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $isAdmin ? 'admin' : $data['role'],
            'is_active' => $isAdmin,
            'currency' => $data['currency'] ?? 'EUR',
            'language' => $data['language'] ?? 'fr',
        ]);

        if ($isAdmin) {
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Compte administrateur créé avec succès.');
        }

        return redirect()->route('login')->with('success', 'Compte créé. En attente d\'activation par l\'administrateur.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
