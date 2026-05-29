<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->is_active && !$user->isAdmin()) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Votre compte est inactif. Contactez l\'administrateur.']);
        }

        if (!empty($roles) && !in_array($user->role, $roles)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
