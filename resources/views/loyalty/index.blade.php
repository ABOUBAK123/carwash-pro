@extends('layouts.app')
@section('title', 'Programme de fidélité')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Programme de fidélité</h1>
        <p class="page-subtitle">{{ $visits->count() }} véhicule(s) suivis · {{ $eligible->count() }} éligible(s)</p>
    </div>
</div>

<!-- Config + Stats -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-6">

    <!-- Config card -->
    <div class="card p-5 xl:col-span-1">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                <i class="fas fa-gift text-amber-400 text-sm"></i>
                <p class="text-sm font-semibold text-white">Configuration</p>
            </div>
            <span class="badge {{ $config->is_active ? 'badge-green' : 'badge-red' }}">
                {{ $config->is_active ? 'Actif' : 'Inactif' }}
            </span>
        </div>

        <form method="POST" action="{{ route('loyalty.configure') }}" class="space-y-4">
            @csrf @method('PATCH')
            <div class="form-group">
                <label class="form-label">Visites requises</label>
                <input type="number" name="required_visits" value="{{ $config->required_visits }}"
                       min="1" max="100" required class="form-input" placeholder="10">
                <p class="text-xs text-slate-600 mt-1">Nombre de lavages pour obtenir la récompense</p>
            </div>
            <div class="form-group">
                <label class="form-label">Réduction accordée (%)</label>
                <input type="number" name="discount_percentage" value="{{ $config->discount_percentage }}"
                       min="0" max="100" step="0.5" required class="form-input" placeholder="10">
            </div>
            <div class="flex items-center gap-3 p-3 bg-[#0d0f1a] rounded-xl border border-[#1e2235]">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ $config->is_active ? 'checked' : '' }}
                       class="w-4 h-4 accent-indigo-500">
                <label for="is_active" class="text-sm text-slate-300 cursor-pointer">Programme actif</label>
            </div>
            <button type="submit" class="btn btn-primary btn-full">
                <i class="fas fa-check"></i> Enregistrer la config
            </button>
        </form>
    </div>

    <!-- KPI Stats -->
    <div class="xl:col-span-2 grid grid-cols-2 gap-4 content-start">
        <div class="stat-card flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-car text-amber-400"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-white">{{ $visits->count() }}</p>
                <p class="text-xs text-slate-500">Véhicules suivis</p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-gift text-emerald-400"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-emerald-400">{{ $eligible->count() }}</p>
                <p class="text-xs text-slate-500">Éligibles à la récompense</p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-hashtag text-indigo-400"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-white">{{ $config->required_visits }}</p>
                <p class="text-xs text-slate-500">Visites requises</p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-violet-500/15 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-percent text-violet-400"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-violet-400">{{ $config->discount_percentage }}%</p>
                <p class="text-xs text-slate-500">Réduction offerte</p>
            </div>
        </div>
    </div>
</div>

<!-- Table des visites -->
<div class="card overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-[#1e2235]">
        <div class="flex items-center gap-2">
            <i class="fas fa-list text-indigo-400 text-sm"></i>
            <p class="text-sm font-semibold text-white">Suivi des véhicules</p>
        </div>
        @if($eligible->count())
        <span class="badge badge-yellow">
            <i class="fas fa-star mr-1 text-[10px]"></i>{{ $eligible->count() }} client(s) à récompenser
        </span>
        @endif
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Véhicule</th>
                <th>Client</th>
                <th>Visites</th>
                <th>Progression</th>
                <th>Dernière visite</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($visits as $v)
            @php
                $pct = min(100, round($v->visits_count / $config->required_visits * 100));
                $isEligible = $v->visits_count >= $config->required_visits;
            @endphp
            <tr>
                <td>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-xs bg-slate-800 text-slate-300 px-2.5 py-1 rounded border border-slate-700 tracking-widest">
                            {{ $v->vehicle_plate }}
                        </span>
                        @if($isEligible)
                        <span class="badge badge-yellow text-[10px]"><i class="fas fa-star mr-1"></i>Éligible</span>
                        @endif
                    </div>
                </td>
                <td>
                    <p class="text-sm text-slate-300">{{ $v->client_name ?? '—' }}</p>
                    @if($v->client_phone)
                    <p class="text-xs text-slate-500">{{ $v->client_phone }}</p>
                    @endif
                </td>
                <td>
                    <span class="text-xl font-extrabold {{ $isEligible ? 'text-emerald-400' : 'text-slate-200' }}">
                        {{ $v->visits_count }}
                    </span>
                    <span class="text-xs text-slate-600">/{{ $config->required_visits }}</span>
                </td>
                <td class="w-36">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-2 bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all {{ $isEligible ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                                 style="width:{{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-slate-500 w-8 text-right">{{ $pct }}%</span>
                    </div>
                </td>
                <td>
                    <p class="text-sm text-slate-400">
                        {{ $v->last_visit_at ? $v->last_visit_at->format('d/m/Y') : '—' }}
                    </p>
                </td>
                <td>
                    <form method="POST" action="{{ route('loyalty.reset', $v) }}"
                          onsubmit="return confirm('Réinitialiser le compteur pour {{ $v->vehicle_plate }} ?')">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline" title="Réinitialiser">
                            <i class="fas fa-rotate-left text-xs"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <i class="fas fa-gift text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucun véhicule suivi pour l'instant</p>
                    <p class="text-slate-700 text-xs mt-1">Les véhicules s'enregistrent automatiquement lors des factures</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
