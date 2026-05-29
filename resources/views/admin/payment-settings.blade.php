@extends('layouts.app')

@section('title', 'Paramètres Paiement — CarWash Pro')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Paramètres de paiement</div>
        <div class="page-sub">Passerelles de paiement et tarification abonnement</div>
    </div>
</div>

<form method="POST" action="{{ route('admin.payment-settings.update') }}">
    @csrf @method('PATCH')
    <div style="display:flex;flex-direction:column;gap:20px;">

        <!-- Abonnement -->
        <div class="card" style="padding:24px;">
            <div style="font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-tag" style="color:var(--brand);"></i> Tarification abonnement
            </div>
            <div class="grid-3" style="gap:16px;">
                <div class="form-group">
                    <label class="form-label">Prix mensuel *</label>
                    <input type="number" name="monthly_price" class="form-input" value="{{ $settings->monthly_price }}" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Prix annuel *</label>
                    <input type="number" name="yearly_price" class="form-input" value="{{ $settings->yearly_price }}" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Devise *</label>
                    <select name="currency" class="form-input">
                        @foreach(['EUR','USD','XOF','XAF','GBP','MAD'] as $cur)
                        <option value="{{ $cur }}" {{ $settings->currency === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Stripe -->
        <div class="card" style="padding:24px;">
            <div style="font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-credit-card" style="color:#635bff;"></i> Stripe
            </div>
            <div class="grid-2" style="gap:16px;">
                <div class="form-group">
                    <label class="form-label">Clé publique</label>
                    <input type="text" name="stripe_public_key" class="form-input text-mono" value="{{ $settings->stripe_public_key }}" placeholder="pk_live_...">
                </div>
                <div class="form-group">
                    <label class="form-label">Clé secrète</label>
                    <input type="password" name="stripe_secret_key" class="form-input text-mono" value="{{ $settings->stripe_secret_key }}" placeholder="sk_live_...">
                </div>
                <div class="form-group">
                    <label class="form-label">Compte marchand</label>
                    <input type="text" name="merchant_account" class="form-input" value="{{ $settings->merchant_account }}" placeholder="acct_...">
                </div>
                <div class="form-group">
                    <label class="form-label">Webhook URL</label>
                    <input type="url" name="webhook_url" class="form-input" value="{{ $settings->webhook_url }}" placeholder="https://...">
                </div>
            </div>
        </div>

        <!-- PayPal -->
        <div class="card" style="padding:24px;">
            <div style="font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
                <i class="fab fa-paypal" style="color:#003087;"></i> PayPal
            </div>
            <div class="form-group" style="max-width:500px;">
                <label class="form-label">Client ID</label>
                <input type="text" name="paypal_client_id" class="form-input text-mono" value="{{ $settings->paypal_client_id }}" placeholder="AYS...">
            </div>
        </div>

        <!-- Mobile Money -->
        <div class="card" style="padding:24px;">
            <div style="font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-mobile-screen-button" style="color:#f59e0b;"></i> Mobile Money
                <label style="margin-left:auto;display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:500;color:#94a3b8;">
                    <input type="hidden" name="enable_mobile_payment" value="0">
                    <input type="checkbox" name="enable_mobile_payment" value="1" {{ $settings->enable_mobile_payment ? 'checked' : '' }}
                        style="width:16px;height:16px;cursor:pointer;"> Activer
                </label>
            </div>
            <div class="grid-2" style="gap:16px;">
                <div class="form-group">
                    <label class="form-label">Orange Money — Clé API</label>
                    <input type="password" name="orange_money_api_key" class="form-input" value="{{ $settings->orange_money_api_key }}" placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label class="form-label">MTN MoMo — Clé API</label>
                    <input type="password" name="mtn_momo_api_key" class="form-input" value="{{ $settings->mtn_momo_api_key }}" placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label class="form-label">Moov Money — Clé API</label>
                    <input type="password" name="moov_money_api_key" class="form-input" value="{{ $settings->moov_money_api_key }}" placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label class="form-label">Wave — Clé API</label>
                    <input type="password" name="wave_api_key" class="form-input" value="{{ $settings->wave_api_key }}" placeholder="••••••••">
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;">
            <button type="submit" class="btn btn-primary" style="padding:10px 28px;">
                <i class="fas fa-save"></i> Sauvegarder les paramètres
            </button>
        </div>
    </div>
</form>
@endsection
