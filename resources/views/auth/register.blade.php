@extends('layouts.auth')
@section('title', 'Créer un compte — CarWash Pro')

@section('content')
<h2 class="text-xl font-bold text-white mb-1">Créer un compte</h2>
<p class="text-sm text-slate-500 mb-6">Rejoignez CarWash Pro dès aujourd'hui</p>

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div class="grid grid-cols-2 gap-3">
        <div class="form-group">
            <label class="form-label">Prénom</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}" required
                   class="form-input" placeholder="Prénom">
        </div>
        <div class="form-group">
            <label class="form-label">Nom</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                   class="form-input" placeholder="Nom">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Adresse email</label>
        <div class="input-icon-wrap">
            <i class="input-icon fas fa-envelope"></i>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="form-input" placeholder="vous@exemple.com">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Téléphone</label>
        <div class="input-icon-wrap">
            <i class="input-icon fas fa-phone"></i>
            <input type="tel" name="phone" value="{{ old('phone') }}"
                   class="form-input" placeholder="+33 6 00 00 00 00">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="form-group">
            <label class="form-label">Rôle</label>
            <select name="role" class="form-input">
                <option value="manager" {{ old('role')=='manager'?'selected':'' }}>Manager</option>
                <option value="receptionist" {{ old('role')=='receptionist'?'selected':'' }}>Réceptionniste</option>
                <option value="employee" {{ old('role')=='employee'?'selected':'' }}>Employé</option>
                <option value="commissionnaire" {{ old('role')=='commissionnaire'?'selected':'' }}>Commissionnaire</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Devise</label>
            <select name="currency" class="form-input">
                <optgroup label="Afrique de l'Ouest">
                    <option value="XOF" {{ old('currency','XOF')=='XOF'?'selected':'' }}>XOF — Franc CFA (UEMOA)</option>
                    <option value="GHS" {{ old('currency')=='GHS'?'selected':'' }}>GHS — Cedi (Ghana)</option>
                    <option value="NGN" {{ old('currency')=='NGN'?'selected':'' }}>NGN — Naira (Nigeria)</option>
                    <option value="GMD" {{ old('currency')=='GMD'?'selected':'' }}>GMD — Dalasi (Gambie)</option>
                    <option value="SLL" {{ old('currency')=='SLL'?'selected':'' }}>SLL — Leone (Sierra Leone)</option>
                    <option value="LRD" {{ old('currency')=='LRD'?'selected':'' }}>LRD — Dollar libérien (Liberia)</option>
                    <option value="MRU" {{ old('currency')=='MRU'?'selected':'' }}>MRU — Ouguiya (Mauritanie)</option>
                    <option value="CVE" {{ old('currency')=='CVE'?'selected':'' }}>CVE — Escudo (Cap-Vert)</option>
                    <option value="GNF" {{ old('currency')=='GNF'?'selected':'' }}>GNF — Franc guinéen (Guinée)</option>
                </optgroup>
                <optgroup label="Autres devises">
                    <option value="EUR" {{ old('currency')=='EUR'?'selected':'' }}>EUR — Euro</option>
                    <option value="USD" {{ old('currency')=='USD'?'selected':'' }}>USD — Dollar américain</option>
                    <option value="GBP" {{ old('currency')=='GBP'?'selected':'' }}>GBP — Livre sterling</option>
                    <option value="MAD" {{ old('currency')=='MAD'?'selected':'' }}>MAD — Dirham marocain</option>
                    <option value="XAF" {{ old('currency')=='XAF'?'selected':'' }}>XAF — Franc CFA (CEMAC)</option>
                </optgroup>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <div class="input-icon-wrap">
            <i class="input-icon fas fa-lock"></i>
            <input type="password" name="password" required
                   class="form-input" placeholder="Minimum 8 caractères">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Confirmer le mot de passe</label>
        <div class="input-icon-wrap">
            <i class="input-icon fas fa-lock"></i>
            <input type="password" name="password_confirmation" required
                   class="form-input" placeholder="Répétez le mot de passe">
        </div>
    </div>

    <button type="submit" class="btn-submit">
        <i class="fas fa-user-plus mr-2"></i>Créer mon compte
    </button>
</form>

<div class="divider-text my-6">ou</div>

<p class="text-center text-sm text-slate-500">
    Déjà un compte ?
    <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition-colors ml-1">
        Se connecter
    </a>
</p>
@endsection
