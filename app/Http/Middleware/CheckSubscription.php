<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    // Routes exemptées de la vérification d'abonnement
    private array $except = [
        'dashboard', 'profile.index', 'profile.update',
        'subscription.index', 'subscription.upgrade',
        'logout',
    ];

    public function handle(Request $request, Closure $next): mixed
    {
        $user = auth()->user();

        // Les admins et commissionnaires ne sont jamais bloqués
        if (!$user || $user->isAdmin() || $user->isCommissionnaire()) {
            return $next($request);
        }

        // Routes exemptées
        if ($request->routeIs($this->except)) {
            return $next($request);
        }

        $carwash = $user->carwash;

        if (!$carwash) {
            return $next($request);
        }

        // Vérifier le statut de l'abonnement
        if ($this->isExpired($carwash)) {
            // Mettre à jour le statut si nécessaire
            if ($carwash->subscription_status !== 'expired') {
                $carwash->update(['subscription_status' => 'expired']);
            }

            return redirect()->route('subscription.index')
                ->with('warning', 'Votre abonnement a expiré. Veuillez renouveler pour continuer.');
        }

        return $next($request);
    }

    private function isExpired($carwash): bool
    {
        if ($carwash->subscription_status === 'cancelled') {
            return true;
        }

        if ($carwash->subscription_status === 'trial') {
            return $carwash->trial_ends_at && now()->isAfter($carwash->trial_ends_at);
        }

        if ($carwash->subscription_status === 'active') {
            return $carwash->subscription_ends_at && now()->isAfter($carwash->subscription_ends_at);
        }

        return $carwash->subscription_status === 'expired';
    }
}
