@extends('layouts.app')
@section('title', 'Espace Commissionnaire — CarWash Pro')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Espace Commissionnaire</div>
        <div class="page-sub">{{ $user->full_name }} — Tableau de bord partenaire</div>
    </div>
    <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Créer un centre
    </button>
</div>

<!-- KPIs -->
<div class="grid-4" style="margin-bottom:28px;">
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Centres créés</div>
        <div style="font-size:28px;font-weight:800;color:#f1f5f9;">{{ $stats['total_centers'] }}</div>
    </div>
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Abonnements actifs</div>
        <div style="font-size:28px;font-weight:800;color:#34d399;">{{ $stats['active_subscriptions'] }}</div>
    </div>
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Commission totale</div>
        <div style="font-size:24px;font-weight:800;color:#a5b4fc;">{{ number_format($stats['total_commission'], 0, ',', ' ') }} <span style="font-size:13px;font-weight:500;">XOF</span></div>
    </div>
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">En attente de paiement</div>
        <div style="font-size:24px;font-weight:800;color:#fbbf24;">{{ number_format($stats['pending_commission'], 0, ',', ' ') }} <span style="font-size:13px;font-weight:500;">XOF</span></div>
    </div>
</div>

<!-- Info commission -->
<div class="card" style="padding:16px 20px;margin-bottom:24px;background:rgba(99,102,241,.05);border-color:rgba(99,102,241,.2);display:flex;align-items:center;gap:12px;">
    <div style="width:36px;height:36px;border-radius:10px;background:rgba(99,102,241,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fas fa-percent" style="color:#a5b4fc;font-size:14px;"></i>
    </div>
    <div style="font-size:13px;color:#94a3b8;line-height:1.6;">
        Vous percevez <strong style="color:#a5b4fc;">3% de commission</strong> sur chaque paiement d'abonnement effectué par les centres que vous avez créés.
        Les commissions sont versées mensuellement sur votre compte.
    </div>
</div>

<!-- Onglets -->
<div style="display:flex;gap:0;border-bottom:2px solid var(--border);margin-bottom:24px;">
    <button onclick="showTab('centers')" id="tab-centers" class="tab-btn active" style="padding:10px 20px;background:none;border:none;font-family:inherit;font-size:13px;font-weight:600;cursor:pointer;color:#a5b4fc;border-bottom:2px solid #6366f1;margin-bottom:-2px;">
        <i class="fas fa-building"></i> Mes centres ({{ $stats['total_centers'] }})
    </button>
    <button onclick="showTab('commissions')" id="tab-commissions" style="padding:10px 20px;background:none;border:none;font-family:inherit;font-size:13px;font-weight:600;cursor:pointer;color:var(--muted);border-bottom:2px solid transparent;margin-bottom:-2px;">
        <i class="fas fa-coins"></i> Commissions ({{ $commissions->count() }})
    </button>
</div>

<!-- Tab: Mes centres -->
<div id="panel-centers">
<div class="card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Centre de lavage</th>
                    <th>Ville</th>
                    <th>Plan</th>
                    <th>Statut abonnement</th>
                    <th>Expiration</th>
                    <th>Commission générée</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carwashes as $carwash)
                @php
                    $planInfo = \App\Models\Plan::findBySlug($carwash->plan ?? 'trial');
                    $centerCommission = $commissions->where('carwash_id', $carwash->id)->sum('commission_amount_xof');
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;color:#f1f5f9;">{{ $carwash->name }}</div>
                        <div style="font-size:12px;color:var(--muted);">{{ $carwash->phone }}</div>
                    </td>
                    <td>{{ $carwash->city }}</td>
                    <td>
                        <span class="badge" style="background:{{ $planInfo?->color ?? '#64748b' }}20;color:{{ $planInfo?->color ?? '#64748b' }};border:1px solid {{ $planInfo?->color ?? '#64748b' }}40;">
                            {{ $planInfo?->name ?? 'Essai' }}
                        </span>
                    </td>
                    <td>
                        @if($carwash->isOnTrial())
                            <span class="badge badge-yellow">Essai — {{ $carwash->daysRemaining() }}j</span>
                        @elseif($carwash->hasActiveSubscription())
                            <span class="badge badge-green">Actif</span>
                        @else
                            <span class="badge badge-red">Expiré</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--muted);">
                        @if($carwash->subscription_ends_at)
                            {{ $carwash->subscription_ends_at->format('d/m/Y') }}
                        @elseif($carwash->trial_ends_at)
                            {{ $carwash->trial_ends_at->format('d/m/Y') }}
                        @else —
                        @endif
                    </td>
                    <td style="font-weight:600;color:#a5b4fc;">
                        {{ $centerCommission > 0 ? number_format($centerCommission, 0, ',', ' ').' XOF' : '—' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted);">
                    Aucun centre créé. <button onclick="document.getElementById('createModal').classList.remove('hidden')" style="background:none;border:none;color:var(--brand);cursor:pointer;font-size:13px;text-decoration:underline;">Créer votre premier centre →</button>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Tab: Commissions -->
<div id="panel-commissions" style="display:none;">
<div class="card">
    <div style="padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:14px;font-weight:600;color:#f1f5f9;">Historique des commissions</span>
        <div style="display:flex;gap:16px;font-size:13px;">
            <span>Total: <strong style="color:#a5b4fc;">{{ number_format($stats['total_commission'], 0, ',', ' ') }} XOF</strong></span>
            <span>Payé: <strong style="color:#34d399;">{{ number_format($stats['paid_commission'], 0, ',', ' ') }} XOF</strong></span>
            <span>En attente: <strong style="color:#fbbf24;">{{ number_format($stats['pending_commission'], 0, ',', ' ') }} XOF</strong></span>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Centre</th>
                    <th>Plan souscrit</th>
                    <th>Montant abonnement</th>
                    <th>Taux</th>
                    <th>Commission</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commissions as $com)
                <tr>
                    <td style="font-size:12px;color:var(--muted);">{{ $com->created_at->format('d/m/Y') }}</td>
                    <td style="font-weight:500;color:#f1f5f9;">{{ $com->carwash->name ?? '—' }}</td>
                    <td>
                        @php $p = \App\Models\Plan::findBySlug($com->plan_slug); @endphp
                        <span class="badge badge-blue">{{ $p?->name ?? $com->plan_slug }}</span>
                    </td>
                    <td>{{ number_format($com->subscription_amount_xof, 0, ',', ' ') }} XOF</td>
                    <td><span class="badge badge-purple">{{ $com->percentage }}%</span></td>
                    <td style="font-weight:700;color:#a5b4fc;">{{ number_format($com->commission_amount_xof, 0, ',', ' ') }} XOF</td>
                    <td>
                        @if($com->status === 'paid')
                            <span class="badge badge-green">Payée le {{ $com->paid_at?->format('d/m/Y') }}</span>
                        @else
                            <span class="badge badge-yellow">En attente</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted);">Aucune commission pour le moment.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Modal: Créer un centre -->
<div id="createModal" class="modal-backdrop hidden">
    <div class="modal-box" style="max-width:500px;">
        <div class="modal-header">
            <div>
                <div class="modal-title">Créer un centre de lavage</div>
                <div class="modal-sub">Le centre sera automatiquement lié à votre compte commissionnaire</div>
            </div>
            <button onclick="document.getElementById('createModal').classList.add('hidden')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px;">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('commissionnaire.create-center') }}">
                @csrf
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div class="form-group">
                        <label class="form-label">Nom du centre *</label>
                        <input type="text" name="name" class="form-input" placeholder="AutoSplash Premium" required>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Ville *</label>
                            <input type="text" name="city" class="form-input" placeholder="Dakar" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone" class="form-input" placeholder="+221 77 000 00 00">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Adresse *</label>
                        <input type="text" name="address" class="form-input" placeholder="15 Avenue Cheikh Anta Diop" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email du centre</label>
                        <input type="email" name="email" class="form-input" placeholder="contact@centre.com">
                    </div>
                    <div style="background:rgba(99,102,241,.06);border:1px solid rgba(99,102,241,.2);border-radius:8px;padding:12px;font-size:12px;color:#94a3b8;">
                        <i class="fas fa-circle-info" style="color:#a5b4fc;margin-right:6px;"></i>
                        Un essai gratuit de 14 jours sera automatiquement activé. Vous percevrez 3% sur chaque paiement d'abonnement.
                    </div>
                    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:6px;">
                        <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="btn btn-outline">Annuler</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer le centre</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tab) {
    ['centers','commissions'].forEach(t => {
        document.getElementById('panel-' + t).style.display = t === tab ? 'block' : 'none';
        const btn = document.getElementById('tab-' + t);
        btn.style.color       = t === tab ? '#a5b4fc' : 'var(--muted)';
        btn.style.borderColor = t === tab ? '#6366f1' : 'transparent';
    });
}
</script>
@endsection
