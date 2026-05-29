@extends('layouts.app')

@section('title', 'Conditions Générales — CarWash Pro')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Conditions générales d'utilisation</div>
        <div class="page-sub">Texte affiché lors des demandes d'inscription</div>
    </div>
</div>

<div style="display:flex;gap:20px;align-items:flex-start;">

    <!-- Editor -->
    <div style="flex:1;">
        <div class="card" style="padding:24px;">
            <form method="POST" action="{{ route('admin.terms-settings.update') }}">
                @csrf @method('PATCH')
                <div class="form-group" style="margin-bottom:16px;">
                    <label class="form-label">Contenu des CGU *</label>
                    <textarea name="content" class="form-input" rows="24" style="min-height:480px;font-family:'Courier New',monospace;font-size:13px;line-height:1.6;" required>{{ $terms->content }}</textarea>
                    <span style="font-size:11px;color:var(--muted);">Le contenu est affiché tel quel. Vous pouvez utiliser du Markdown pour la mise en forme.</span>
                </div>
                <div style="display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding:10px 28px;">
                        <i class="fas fa-save"></i> Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview info -->
    <div style="width:280px;flex-shrink:0;display:flex;flex-direction:column;gap:16px;">
        <div class="card" style="padding:20px;">
            <div style="font-size:13px;font-weight:600;color:#f1f5f9;margin-bottom:10px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-eye" style="color:var(--brand);"></i> Aperçu
            </div>
            <div style="font-size:12px;color:var(--muted);line-height:1.6;">
                Ce texte est affiché aux propriétaires lors de leur demande d'inscription via la page publique d'inscription.
            </div>
        </div>

        <div class="card" style="padding:20px;">
            <div style="font-size:13px;font-weight:600;color:#f1f5f9;margin-bottom:10px;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-circle-info" style="color:#fbbf24;"></i> Conseils
            </div>
            <ul style="font-size:12px;color:var(--muted);line-height:1.8;padding-left:14px;">
                <li>Utilisez <code style="color:#a5b4fc;"># Titre</code> pour les titres</li>
                <li>Utilisez <code style="color:#a5b4fc;">## Section</code> pour les sous-titres</li>
                <li>Sautez une ligne entre les paragraphes</li>
                <li>Utilisez <code style="color:#a5b4fc;">**texte**</code> pour le gras</li>
            </ul>
        </div>

        <div class="card" style="padding:20px;">
            <div style="font-size:12px;font-weight:600;color:#94a3b8;margin-bottom:8px;">Dernière modification</div>
            <div style="font-size:13px;color:#cbd5e1;">{{ $terms->updated_at->format('d/m/Y à H:i') }}</div>
        </div>
    </div>
</div>
@endsection
