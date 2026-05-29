@extends('layouts.app')

@section('title', 'Paramètres Email — CarWash Pro')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Paramètres email</div>
        <div class="page-sub">Configuration SMTP et notifications</div>
    </div>
</div>

<div style="display:flex;flex-direction:column;gap:20px;">

    <!-- SMTP Config -->
    <div class="card" style="padding:24px;">
        <div style="font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-server" style="color:var(--brand);"></i> Serveur SMTP
        </div>
        <form method="POST" action="{{ route('admin.email-settings.update') }}">
            @csrf @method('PATCH')
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div class="grid-2" style="gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Hôte SMTP *</label>
                        <input type="text" name="smtp_host" class="form-input" value="{{ $settings->smtp_host }}" placeholder="smtp.gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Port *</label>
                        <input type="number" name="smtp_port" class="form-input" value="{{ $settings->smtp_port }}" placeholder="587" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Utilisateur SMTP</label>
                        <input type="text" name="smtp_user" class="form-input" value="{{ $settings->smtp_user }}" placeholder="user@gmail.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mot de passe SMTP</label>
                        <input type="password" name="smtp_password" class="form-input" placeholder="Laisser vide pour conserver">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Chiffrement *</label>
                        <select name="smtp_encryption" class="form-input">
                            <option value="tls"  {{ $settings->smtp_encryption === 'tls'  ? 'selected' : '' }}>TLS (recommandé)</option>
                            <option value="ssl"  {{ $settings->smtp_encryption === 'ssl'  ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ $settings->smtp_encryption === 'none' ? 'selected' : '' }}>Aucun</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom expéditeur *</label>
                        <input type="text" name="from_name" class="form-input" value="{{ $settings->from_name }}" required>
                    </div>
                    <div class="form-group col-2">
                        <label class="form-label">Email expéditeur</label>
                        <input type="email" name="from_email" class="form-input" value="{{ $settings->from_email }}" placeholder="noreply@example.com">
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#94a3b8;">
                        <input type="hidden" name="enable_notifications" value="0">
                        <input type="checkbox" name="enable_notifications" value="1" {{ $settings->enable_notifications ? 'checked' : '' }} style="width:16px;height:16px;">
                        Activer les notifications email automatiques
                    </label>
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Sauvegarder
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Test Email -->
    <div class="card" style="padding:24px;">
        <div style="font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-paper-plane" style="color:#34d399;"></i> Envoyer un email de test
        </div>
        <form method="POST" action="{{ route('admin.email-settings.test') }}">
            @csrf
            <div style="display:flex;gap:10px;align-items:flex-end;max-width:480px;">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Adresse de test</label>
                    <input type="email" name="test_email" class="form-input" placeholder="votre@email.com" required>
                </div>
                <button type="submit" class="btn btn-success" style="flex-shrink:0;">
                    <i class="fas fa-paper-plane"></i> Envoyer
                </button>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="card" style="padding:20px 24px;background:rgba(99,102,241,.05);border-color:rgba(99,102,241,.2);">
        <div style="display:flex;gap:12px;align-items:flex-start;">
            <i class="fas fa-circle-info" style="color:var(--brand);margin-top:2px;"></i>
            <div>
                <div style="font-size:13px;font-weight:600;color:#a5b4fc;margin-bottom:4px;">Configuration Gmail</div>
                <div style="font-size:12px;color:var(--muted);line-height:1.6;">
                    Pour Gmail, utilisez <strong style="color:#cbd5e1;">smtp.gmail.com</strong>, port <strong style="color:#cbd5e1;">587</strong>, chiffrement TLS.
                    Le mot de passe doit être un <em>mot de passe d'application</em> (pas votre mot de passe Gmail).
                    Activez la validation en 2 étapes puis créez un mot de passe d'application dans les paramètres de sécurité Google.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
