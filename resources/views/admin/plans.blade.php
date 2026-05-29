@extends('layouts.app')
@section('title', 'Plans d\'abonnement — CarWash Pro')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Plans d'abonnement</div>
        <div class="page-sub">Paramétrer les prix, limites et fonctionnalités de chaque plan</div>
    </div>
</div>

<div style="display:flex;flex-direction:column;gap:24px;">
@foreach($plans as $plan)
<div class="card" style="padding:0;overflow:hidden;{{ $plan->badge ? 'border-color:#6366f1;' : '' }}">

    <!-- En-tête plan -->
    <div style="padding:18px 24px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);background:rgba(255,255,255,.02);">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:14px;height:14px;border-radius:50%;background:{{ $plan->color }};flex-shrink:0;"></div>
            <div>
                <span style="font-size:15px;font-weight:700;color:#fff;">{{ $plan->name }}</span>
                @if($plan->badge)
                <span class="badge badge-blue" style="margin-left:8px;font-size:10px;">{{ $plan->badge }}</span>
                @endif
                <span class="badge {{ $plan->is_active ? 'badge-green' : 'badge-gray' }}" style="margin-left:6px;font-size:10px;">
                    {{ $plan->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <form method="POST" action="{{ route('admin.plans.toggle', $plan) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-{{ $plan->is_active ? 'warning' : 'success' }} btn-sm">
                    <i class="fas fa-{{ $plan->is_active ? 'eye-slash' : 'eye' }}"></i>
                    {{ $plan->is_active ? 'Désactiver' : 'Activer' }}
                </button>
            </form>
            <button onclick="toggleForm('form-{{ $plan->slug }}')" class="btn btn-outline btn-sm">
                <i class="fas fa-pen"></i> Modifier
            </button>
        </div>
    </div>

    <!-- Résumé rapide -->
    <div style="padding:16px 24px;display:flex;gap:32px;flex-wrap:wrap;">
        <div>
            <div style="font-size:10px;color:var(--muted);font-weight:600;text-transform:uppercase;margin-bottom:2px;">Prix / mois</div>
            <div style="font-size:18px;font-weight:800;color:#fff;">
                {{ $plan->price_monthly_xof > 0 ? number_format($plan->price_monthly_xof, 0, ',', ' ').' XOF' : 'Gratuit' }}
            </div>
            @if($plan->price_monthly_eur > 0)
            <div style="font-size:11px;color:var(--muted);">≈ {{ number_format($plan->price_monthly_eur, 2, ',', ' ') }} €</div>
            @endif
        </div>
        <div>
            <div style="font-size:10px;color:var(--muted);font-weight:600;text-transform:uppercase;margin-bottom:2px;">Employés max</div>
            <div style="font-size:16px;font-weight:700;color:#cbd5e1;">{{ $plan->maxEmployeesLabel() }}</div>
        </div>
        <div>
            <div style="font-size:10px;color:var(--muted);font-weight:600;text-transform:uppercase;margin-bottom:2px;">Factures / mois</div>
            <div style="font-size:16px;font-weight:700;color:#cbd5e1;">{{ $plan->maxInvoicesLabel() }}</div>
        </div>
        @if($plan->trial_days > 0)
        <div>
            <div style="font-size:10px;color:var(--muted);font-weight:600;text-transform:uppercase;margin-bottom:2px;">Essai gratuit</div>
            <div style="font-size:16px;font-weight:700;color:#fbbf24;">{{ $plan->trial_days }} jours</div>
        </div>
        @endif
    </div>

    <!-- Formulaire de modification (caché par défaut) -->
    <div id="form-{{ $plan->slug }}" style="display:none;border-top:1px solid var(--border);">
        <form method="POST" action="{{ route('admin.plans.update', $plan) }}" style="padding:24px;">
            @csrf @method('PATCH')
            <div style="display:flex;flex-direction:column;gap:18px;">

                <div class="grid-3" style="gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nom du plan *</label>
                        <input type="text" name="name" class="form-input" value="{{ $plan->name }}" required>
                    </div>
                    <div class="form-group col-2">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-input" value="{{ $plan->description }}">
                    </div>
                </div>

                <div class="grid-4" style="gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Prix mensuel (XOF) *</label>
                        <input type="number" name="price_monthly_xof" class="form-input" value="{{ $plan->price_monthly_xof }}" min="0" step="100" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix mensuel (EUR) *</label>
                        <input type="number" name="price_monthly_eur" class="form-input" value="{{ $plan->price_monthly_eur }}" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Employés max (-1=∞) *</label>
                        <input type="number" name="max_employees" class="form-input" value="{{ $plan->max_employees }}" min="-1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Factures/mois (-1=∞) *</label>
                        <input type="number" name="max_invoices" class="form-input" value="{{ $plan->max_invoices }}" min="-1" required>
                    </div>
                </div>

                <div class="grid-4" style="gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Jours d'essai gratuit</label>
                        <input type="number" name="trial_days" class="form-input" value="{{ $plan->trial_days }}" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Badge (ex: Populaire)</label>
                        <input type="text" name="badge" class="form-input" value="{{ $plan->badge }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Couleur</label>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <input type="color" name="color" value="{{ $plan->color }}" style="width:40px;height:36px;border:1px solid var(--border);border-radius:6px;background:var(--bg);cursor:pointer;padding:2px;">
                            <input type="text" id="colorText-{{ $plan->slug }}" class="form-input" value="{{ $plan->color }}" style="font-family:monospace;" oninput="document.querySelector('[name=color]').value=this.value">
                        </div>
                    </div>
                    <div class="form-group" style="justify-content:center;">
                        <label class="form-label">Plan actif</label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-top:6px;">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }} style="width:18px;height:18px;">
                            <span style="font-size:13px;color:#94a3b8;">Visible publiquement</span>
                        </label>
                    </div>
                </div>

                <!-- Fonctionnalités -->
                <div>
                    <div style="font-size:12px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px;">Fonctionnalités</div>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        @foreach($plan->features ?? [] as $i => $feature)
                        <div style="display:flex;align-items:center;gap:10px;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:10px 14px;">
                            <input type="hidden" name="features[{{ $i }}][label]" value="{{ $feature['label'] }}">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;flex:1;">
                                <input type="hidden" name="features[{{ $i }}][included]" value="0">
                                <input type="checkbox" name="features[{{ $i }}][included]" value="1"
                                    {{ ($feature['included'] ?? false) ? 'checked' : '' }}
                                    style="width:16px;height:16px;cursor:pointer;">
                                <span style="font-size:13px;color:#cbd5e1;">{{ $feature['label'] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="toggleForm('form-{{ $plan->slug }}')" class="btn btn-outline">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Sauvegarder
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
</div>

<script>
function toggleForm(id) {
    const el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>
@endsection
