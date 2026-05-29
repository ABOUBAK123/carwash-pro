@extends('layouts.app')

@section('title', 'Mon abonnement — CarWash Pro')

@section('content')

@if(session('warning'))
<div class="alert" style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.25);color:#fbbf24;margin-bottom:20px;">
    <i class="fas fa-triangle-exclamation" style="flex-shrink:0;margin-top:1px;"></i>
    <span>{{ session('warning') }}</span>
</div>
@endif

<div class="page-header">
    <div>
        <div class="page-title">Mon abonnement</div>
        <div class="page-sub">{{ $carwash->name }}</div>
    </div>
    <a href="{{ route('pricing') }}" target="_blank" class="btn btn-outline">
        <i class="fas fa-arrow-up-right-from-square"></i> Voir la page tarifs
    </a>
</div>

<!-- Statut actuel -->
<div class="card" style="padding:24px;margin-bottom:24px;background:linear-gradient(135deg,rgba(99,102,241,.08),rgba(139,92,246,.05));">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div style="display:flex;align-items:center;gap:16px;">
            <div style="width:52px;height:52px;border-radius:14px;background:rgba(99,102,241,.15);display:flex;align-items:center;justify-content:center;font-size:22px;">
                @if($carwash->plan === 'pro') 👑
                @elseif($carwash->plan === 'business') 🏢
                @elseif($carwash->plan === 'starter') 🚀
                @else 🌱
                @endif
            </div>
            <div>
                <div style="font-size:20px;font-weight:800;color:#fff;">Plan {{ ucfirst($carwash->plan ?? 'trial') }}</div>
                <div style="font-size:13px;color:var(--muted);margin-top:3px;">
                    @if($carwash->isOnTrial())
                        <span style="color:#fbbf24;"><i class="fas fa-clock"></i> Essai gratuit — {{ $carwash->daysRemaining() }} jour(s) restant(s)</span>
                    @elseif($carwash->hasActiveSubscription())
                        <span style="color:#34d399;"><i class="fas fa-circle-check"></i> Actif jusqu'au {{ $carwash->subscription_ends_at->format('d/m/Y') }}</span>
                    @else
                        <span style="color:#fb7185;"><i class="fas fa-circle-xmark"></i> Abonnement expiré</span>
                    @endif
                </div>
            </div>
        </div>
        <div>
            @if($carwash->subscriptionExpired())
                <span class="badge badge-red" style="font-size:13px;padding:6px 14px;">Expiré</span>
            @elseif($carwash->isOnTrial())
                <span class="badge badge-yellow" style="font-size:13px;padding:6px 14px;">Essai gratuit</span>
            @else
                <span class="badge badge-green" style="font-size:13px;padding:6px 14px;">Actif</span>
            @endif
        </div>
    </div>

    @if($carwash->isOnTrial() && $carwash->daysRemaining() <= 5)
    <div style="margin-top:16px;padding:12px 16px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);border-radius:8px;font-size:13px;color:#fbbf24;">
        <i class="fas fa-triangle-exclamation"></i>
        Votre essai expire dans <strong>{{ $carwash->daysRemaining() }} jour(s)</strong>. Passez à un plan payant pour continuer à utiliser CarWash Pro.
    </div>
    @endif
</div>

<!-- Grille des plans -->
<div style="font-size:15px;font-weight:700;color:#f1f5f9;margin-bottom:18px;">Choisir un plan</div>

<div class="grid-3" style="gap:20px;margin-bottom:32px;">
    @foreach(\App\Models\SubscriptionPlan::paid() as $plan)
    <div class="card" style="padding:24px;position:relative;{{ $plan['slug'] === 'pro' ? 'border-color:#6366f1;background:linear-gradient(180deg,rgba(99,102,241,.06),var(--surface));' : '' }}{{ ($carwash->plan === $plan['slug'] && !$carwash->subscriptionExpired()) ? 'border-color:#34d399;' : '' }}">

        @if($plan['badge'])
        <div style="position:absolute;top:-10px;left:50%;transform:translateX(-50%);background:linear-gradient(90deg,#6366f1,#8b5cf6);color:#fff;font-size:10px;font-weight:700;padding:3px 14px;border-radius:20px;">
            ⭐ {{ $plan['badge'] }}
        </div>
        @endif

        @if($carwash->plan === $plan['slug'] && !$carwash->subscriptionExpired())
        <div style="position:absolute;top:12px;right:12px;">
            <span class="badge badge-green" style="font-size:10px;">Actuel</span>
        </div>
        @endif

        <div style="font-size:16px;font-weight:800;color:#fff;margin-bottom:4px;">{{ $plan['name'] }}</div>
        <div style="margin-bottom:16px;">
            <span style="font-size:26px;font-weight:900;color:#fff;">{{ number_format($plan['price_xof'], 0, ',', ' ') }}</span>
            <span style="font-size:13px;color:var(--muted);"> XOF / mois</span>
            <div style="font-size:12px;color:var(--muted);">≈ {{ number_format($plan['price_eur'], 2, ',', ' ') }} € / mois</div>
        </div>

        <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;margin-bottom:20px;">
            @foreach($plan['features'] as $f)
            <li style="display:flex;align-items:center;gap:8px;font-size:12px;{{ $f['included'] ? 'color:#cbd5e1;' : 'color:#3d4d66;' }}">
                <i class="fas fa-{{ $f['included'] ? 'check' : 'times' }}" style="font-size:9px;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:{{ $f['included'] ? 'rgba(16,185,129,.15)' : 'rgba(100,116,139,.08)' }};color:{{ $f['included'] ? '#34d399' : '#3d4d66' }};flex-shrink:0;"></i>
                {{ $f['label'] }}
            </li>
            @endforeach
        </ul>

        @if($carwash->plan === $plan['slug'] && !$carwash->subscriptionExpired())
            <form method="POST" action="{{ route('subscription.upgrade') }}">
                @csrf
                <input type="hidden" name="plan" value="{{ $plan['slug'] }}">
                <button type="submit" class="btn btn-success btn-full">
                    <i class="fas fa-rotate"></i> Renouveler
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('subscription.upgrade') }}">
                @csrf
                <input type="hidden" name="plan" value="{{ $plan['slug'] }}">
                <button type="submit" class="btn {{ $plan['slug'] === 'pro' ? 'btn-primary' : 'btn-outline' }} btn-full">
                    <i class="fas fa-arrow-{{ $plan['price_xof'] > ($carwash->planDetails()['price_xof'] ?? 0) ? 'up' : 'down' }}"></i>
                    Passer à {{ $plan['name'] }}
                </button>
            </form>
        @endif
    </div>
    @endforeach
</div>

<!-- Historique -->
<div class="card" style="padding:20px 24px;">
    <div style="font-size:13px;font-weight:600;color:#f1f5f9;margin-bottom:4px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-circle-info" style="color:var(--brand);"></i> Informations de paiement
    </div>
    <div style="font-size:13px;color:var(--muted);line-height:1.7;">
        Pour les paiements Mobile Money (Orange Money, MTN MoMo, Wave), contactez le support à
        <span style="color:#a5b4fc;">support@carwashpro.com</span> avec votre numéro de centre.
        Le plan sera activé manuellement sous 24h.
    </div>
</div>

@endsection
