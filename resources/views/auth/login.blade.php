@extends('layouts.auth')
@section('title', 'Connexion — CarWash Pro')

@section('content')
<h2 class="text-xl font-bold text-white mb-1">Bon retour 👋</h2>
<p class="text-sm text-slate-500 mb-6">Connectez-vous à votre espace de travail</p>

<form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div class="form-group">
        <label class="form-label">Adresse email</label>
        <div class="input-icon-wrap">
            <i class="input-icon fas fa-envelope"></i>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                   class="form-input" placeholder="vous@exemple.com">
        </div>
    </div>

    <div class="form-group">
        <div class="flex items-center justify-between">
            <label class="form-label">Mot de passe</label>
        </div>
        <div class="input-icon-wrap">
            <i class="input-icon fas fa-lock"></i>
            <input type="password" name="password" required autocomplete="current-password"
                   class="form-input" placeholder="••••••••">
        </div>
    </div>

    <div class="flex items-center gap-2 py-1">
        <input type="checkbox" name="remember" id="remember"
               class="w-4 h-4 rounded border-slate-600 bg-slate-800 accent-indigo-500">
        <label for="remember" class="text-sm text-slate-400">Se souvenir de moi</label>
    </div>

    <button type="submit" class="btn-submit">
        <i class="fas fa-arrow-right-to-bracket mr-2"></i>Se connecter
    </button>
</form>

<div class="divider-text my-6">ou</div>

<p class="text-center text-sm text-slate-500">
    Pas encore de compte ?
    <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition-colors ml-1">
        Créer un compte
    </a>
</p>
@endsection
