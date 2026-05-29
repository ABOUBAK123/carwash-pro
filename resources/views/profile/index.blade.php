@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Mon profil</h1>
        <p class="page-subtitle">Gérez vos informations personnelles et préférences</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Avatar + infos rapides -->
    <div class="card p-6 flex flex-col items-center text-center">
        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-2xl font-extrabold text-white mb-4 shadow-lg shadow-indigo-500/30">
            {{ strtoupper(substr($user->first_name,0,1)) }}{{ strtoupper(substr($user->last_name,0,1)) }}
        </div>
        <p class="text-lg font-bold text-white">{{ $user->full_name }}</p>
        <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
        <div class="mt-3">
            @php
            $roleBadge = ['admin'=>'badge-red','manager'=>'badge-blue','receptionist'=>'badge-yellow','employee'=>'badge-green'][$user->role] ?? 'badge-gray';
            $roleLabel = ['admin'=>'Administrateur','manager'=>'Manager','receptionist'=>'Réceptionniste','employee'=>'Employé'][$user->role] ?? $user->role;
            @endphp
            <span class="badge {{ $roleBadge }}">{{ $roleLabel }}</span>
        </div>
        @if($user->carwash)
        <div class="mt-4 w-full p-3 bg-[#0d0f1a] rounded-xl border border-[#1e2235] text-left">
            <p class="text-xs text-slate-500 uppercase font-semibold tracking-wider mb-1">Centre assigné</p>
            <p class="text-sm font-semibold text-slate-200">{{ $user->carwash->name }}</p>
            <p class="text-xs text-slate-500">{{ $user->carwash->city }}</p>
        </div>
        @endif
        <div class="mt-3 w-full p-3 bg-[#0d0f1a] rounded-xl border border-[#1e2235] text-left">
            <p class="text-xs text-slate-500 uppercase font-semibold tracking-wider mb-1">Membre depuis</p>
            <p class="text-sm text-slate-300">{{ $user->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Formulaire infos + mot de passe -->
    <div class="xl:col-span-2 space-y-5">

        <!-- Infos personnelles -->
        <div class="card p-6">
            <div class="flex items-center gap-2 mb-5">
                <i class="fas fa-user text-indigo-400 text-sm"></i>
                <p class="text-sm font-semibold text-white">Informations personnelles</p>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                               required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                               required class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="+33 6 00 00 00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" value="{{ $user->email }}" disabled class="form-input opacity-50" title="L'email ne peut pas être modifié">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Langue</label>
                        <select name="language" class="form-input">
                            <option value="fr" {{ ($user->language ?? 'fr')==='fr'?'selected':'' }}>Français</option>
                            <option value="en" {{ ($user->language ?? '')==='en'?'selected':'' }}>English</option>
                            <option value="ar" {{ ($user->language ?? '')==='ar'?'selected':'' }}>العربية</option>
                            <option value="es" {{ ($user->language ?? '')==='es'?'selected':'' }}>Español</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Devise</label>
                        <select name="currency" class="form-input">
                            @foreach(['EUR'=>'EUR — €','USD'=>'USD — $','GBP'=>'GBP — £','XOF'=>'XOF — CFA','MAD'=>'MAD — DH'] as $k=>$l)
                            <option value="{{ $k }}" {{ ($user->currency ?? 'EUR')===$k?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Sauvegarder les modifications
                    </button>
                </div>
            </form>
        </div>

        <!-- Changement mot de passe -->
        <div class="card p-6">
            <div class="flex items-center gap-2 mb-5">
                <i class="fas fa-lock text-amber-400 text-sm"></i>
                <p class="text-sm font-semibold text-white">Changer le mot de passe</p>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf @method('PATCH')
                <!-- Champs identité requis pour la validation -->
                <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                <div class="form-group">
                    <label class="form-label">Mot de passe actuel *</label>
                    <input type="password" name="current_password" class="form-input" placeholder="Votre mot de passe actuel">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Nouveau mot de passe *</label>
                        <input type="password" name="password" class="form-input" placeholder="Min. 8 caractères">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirmer *</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Répéter le mot de passe">
                    </div>
                </div>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-key"></i> Changer le mot de passe
                </button>
            </form>
        </div>

    </div>
</div>

@endsection
