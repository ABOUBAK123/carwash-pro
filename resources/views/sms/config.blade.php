@extends('layouts.app')
@section('title', 'Configuration SMS')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Configuration SMS</h1>
        <p class="page-subtitle">Paramétrez les notifications SMS de votre centre</p>
    </div>
    <span class="badge {{ $config->auto_send ? 'badge-green' : 'badge-gray' }}">
        <span class="w-1.5 h-1.5 rounded-full {{ $config->auto_send ? 'bg-emerald-400' : 'bg-slate-500' }} mr-1.5"></span>
        {{ $config->auto_send ? 'Notifications actives' : 'Notifications désactivées' }}
    </span>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Formulaire config -->
    <div class="xl:col-span-2 card p-6">
        <div class="flex items-center gap-2 mb-6">
            <i class="fas fa-message text-indigo-400 text-sm"></i>
            <p class="text-sm font-semibold text-white">Paramètres du fournisseur</p>
        </div>

        <form method="POST" action="{{ route('sms.update') }}" class="space-y-5">
            @csrf @method('PATCH')

            <div class="form-group">
                <label class="form-label">Fournisseur SMS *</label>
                <select name="provider" id="provider" onchange="showProviderInfo()" class="form-input">
                    <option value="custom"  {{ $config->provider==='custom'?'selected':'' }}>Custom API</option>
                    <option value="twilio"  {{ $config->provider==='twilio'?'selected':'' }}>Twilio</option>
                    <option value="nexmo"   {{ $config->provider==='nexmo'?'selected':'' }}>Vonage (Nexmo)</option>
                    <option value="orange"  {{ $config->provider==='orange'?'selected':'' }}>Orange SMS API</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Clé API / Token *</label>
                <div class="relative">
                    <input type="password" name="api_key" id="api_key"
                           value="{{ $config->api_key }}"
                           class="form-input pr-10" placeholder="sk_live_xxxxxxxxxxxx">
                    <button type="button" onclick="toggleApiKey()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300">
                        <i class="fas fa-eye text-sm" id="eye-icon"></i>
                    </button>
                </div>
                <p class="text-xs text-slate-600 mt-1">Votre clé API confidentielle — ne la partagez jamais</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="form-group">
                    <label class="form-label">Nom expéditeur *</label>
                    <input type="text" name="sender_name" value="{{ $config->sender_name }}"
                           required maxlength="11" class="form-input" placeholder="CarWash">
                    <p class="text-xs text-slate-600 mt-1">Max 11 caractères</p>
                </div>
                <div class="form-group flex flex-col justify-end">
                    <div class="flex items-center gap-3 p-3 bg-[#0d0f1a] rounded-xl border border-[#1e2235] h-[46px]">
                        <input type="checkbox" name="auto_send" id="auto_send" value="1"
                               {{ $config->auto_send ? 'checked' : '' }}
                               class="w-4 h-4 accent-indigo-500 flex-shrink-0">
                        <label for="auto_send" class="text-sm text-slate-300 cursor-pointer">Envoi automatique</label>
                    </div>
                </div>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Sauvegarder la configuration
                </button>
            </div>
        </form>
    </div>

    <!-- Info & aperçu -->
    <div class="space-y-4">

        <!-- Status card -->
        <div class="card p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-4">Statut actuel</p>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Fournisseur</span>
                    <span class="font-semibold text-slate-200 capitalize">{{ $config->provider }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Expéditeur</span>
                    <span class="font-semibold text-slate-200">{{ $config->sender_name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Clé API</span>
                    <span class="font-semibold text-slate-200">
                        {{ $config->api_key ? '••••••••'.substr($config->api_key, -4) : 'Non configurée' }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Auto-envoi</span>
                    <span class="badge {{ $config->auto_send ? 'badge-green' : 'badge-gray' }}">
                        {{ $config->auto_send ? 'Activé' : 'Désactivé' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Événements déclencheurs -->
        <div class="card p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-4">Notifications envoyées</p>
            <div class="space-y-2.5">
                @foreach([
                    ['icon'=>'fas fa-play','color'=>'text-indigo-400','label'=>'Démarrage du service'],
                    ['icon'=>'fas fa-check','color'=>'text-emerald-400','label'=>'Service terminé'],
                    ['icon'=>'fas fa-calendar','color'=>'text-amber-400','label'=>'Rappel de RDV'],
                ] as $event)
                <div class="flex items-center gap-3 p-2.5 bg-[#0d0f1a] rounded-lg border border-[#1e2235]">
                    <i class="{{ $event['icon'] }} {{ $event['color'] }} text-xs flex-shrink-0"></i>
                    <span class="text-xs text-slate-300">{{ $event['label'] }}</span>
                    <span class="ml-auto badge badge-blue text-[10px]">Auto</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<script>
function toggleApiKey() {
    const input = document.getElementById('api_key');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash text-sm';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye text-sm';
    }
}
</script>
@endsection
