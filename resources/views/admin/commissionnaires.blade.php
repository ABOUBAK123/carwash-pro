@extends('layouts.app')
@section('title', 'Commissionnaires — CarWash Pro')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Commissionnaires</div>
        <div class="page-sub">Gestion des comptes partenaires et suivi des commissions</div>
    </div>
    <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Nouveau commissionnaire
    </button>
</div>

<!-- KPIs -->
<div class="grid-4" style="margin-bottom:24px;">
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Total</div>
        <div style="font-size:28px;font-weight:800;color:#f1f5f9;">{{ $stats['total'] }}</div>
    </div>
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Actifs / Vérifiés</div>
        <div style="font-size:28px;font-weight:800;color:#34d399;">{{ $stats['active'] }} <span style="font-size:14px;color:var(--muted);">/ {{ $stats['verified'] }}</span></div>
    </div>
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Commissions totales</div>
        <div style="font-size:22px;font-weight:800;color:#a5b4fc;">{{ number_format($stats['total_commission'], 0, ',', ' ') }} <span style="font-size:12px;">XOF</span></div>
    </div>
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">En attente de paiement</div>
        <div style="font-size:22px;font-weight:800;color:#fbbf24;">{{ number_format($stats['pending'], 0, ',', ' ') }} <span style="font-size:12px;">XOF</span></div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Commissionnaire</th>
                    <th>Contact</th>
                    <th>Pièce d'identité</th>
                    <th>Centres</th>
                    <th>Commissions</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commissionnaires as $com)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;">
                                {{ strtoupper(substr($com->first_name,0,1).substr($com->last_name,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:#f1f5f9;">{{ $com->full_name }}</div>
                                <div style="font-size:11px;color:var(--muted);">Depuis {{ $com->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13px;">{{ $com->email }}</div>
                        @if($com->phone)
                        <div style="font-size:12px;color:var(--muted);">{{ $com->phone }}</div>
                        @endif
                    </td>
                    <td>
                        @if($com->identity_document)
                            <div style="display:flex;align-items:center;gap:6px;">
                                @if($com->identity_verified)
                                    <span class="badge badge-green"><i class="fas fa-shield-check"></i> Vérifiée</span>
                                @else
                                    <span class="badge badge-yellow"><i class="fas fa-clock"></i> En attente</span>
                                @endif
                                <a href="{{ route('admin.commissionnaires.document', $com) }}" class="btn btn-outline btn-sm btn-icon" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.commissionnaires.verify', $com) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-{{ $com->identity_verified ? 'warning' : 'success' }} btn-sm btn-icon" title="{{ $com->identity_verified ? 'Marquer non vérifiée' : 'Marquer vérifiée' }}">
                                        <i class="fas fa-{{ $com->identity_verified ? 'times' : 'check' }}"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <span style="font-size:12px;color:var(--muted);font-style:italic;">Non fournie</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-size:16px;font-weight:700;color:#f1f5f9;">{{ $com->referred_carwashes_count }}</span>
                        <span style="font-size:12px;color:var(--muted);"> centre(s)</span>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600;color:#a5b4fc;">{{ number_format($com->total_commission, 0, ',', ' ') }} XOF</div>
                        <div style="font-size:11px;color:#fbbf24;">{{ number_format($com->pending_commission, 0, ',', ' ') }} XOF en attente</div>
                    </td>
                    <td>
                        @if($com->is_active)
                            <span class="badge badge-green">Actif</span>
                        @else
                            <span class="badge badge-red">Inactif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:5px;flex-wrap:wrap;">
                            <button onclick="toggleDetails('detail-{{ $com->id }}')" class="btn btn-outline btn-sm btn-icon" title="Voir commissions">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($com->pending_commission > 0)
                            <form method="POST" action="{{ route('admin.commissionnaires.mark-paid', $com) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm btn-icon" title="Marquer commissions payées" onclick="return confirm('Marquer toutes les commissions en attente comme payées ?')">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.commissionnaires.toggle', $com) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-{{ $com->is_active ? 'warning' : 'success' }} btn-sm btn-icon" title="{{ $com->is_active ? 'Désactiver' : 'Activer' }}">
                                    <i class="fas fa-{{ $com->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.commissionnaires.destroy', $com) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Supprimer" onclick="return confirm('Supprimer ce compte et toutes ses commissions ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Détail commissions (caché) -->
                <tr id="detail-{{ $com->id }}" class="hidden">
                    <td colspan="7" style="padding:0;">
                        <div style="padding:16px 24px;background:var(--bg);border-bottom:1px solid var(--border);">
                            <div style="font-size:12px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px;">
                                Commissions de {{ $com->full_name }}
                            </div>
                            @if($com->commissions_list->isEmpty())
                                <p style="font-size:13px;color:var(--muted);font-style:italic;">Aucune commission enregistrée.</p>
                            @else
                            <table style="width:100%;border-collapse:collapse;font-size:12px;">
                                <thead>
                                    <tr style="color:var(--muted);">
                                        <th style="text-align:left;padding:6px 12px;font-weight:600;">Date</th>
                                        <th style="text-align:left;padding:6px 12px;font-weight:600;">Centre</th>
                                        <th style="text-align:left;padding:6px 12px;font-weight:600;">Plan</th>
                                        <th style="text-align:right;padding:6px 12px;font-weight:600;">Abonnement</th>
                                        <th style="text-align:right;padding:6px 12px;font-weight:600;">Commission (3%)</th>
                                        <th style="text-align:left;padding:6px 12px;font-weight:600;">Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($com->commissions_list as $c)
                                    <tr style="border-top:1px solid var(--border2);">
                                        <td style="padding:8px 12px;color:var(--muted);">{{ $c->created_at->format('d/m/Y') }}</td>
                                        <td style="padding:8px 12px;color:#cbd5e1;">{{ $c->carwash->name ?? '—' }}</td>
                                        <td style="padding:8px 12px;">
                                            <span class="badge badge-blue" style="font-size:10px;">{{ $c->plan_slug }}</span>
                                        </td>
                                        <td style="padding:8px 12px;text-align:right;color:#cbd5e1;">{{ number_format($c->subscription_amount_xof, 0, ',', ' ') }} XOF</td>
                                        <td style="padding:8px 12px;text-align:right;font-weight:700;color:#a5b4fc;">{{ number_format($c->commission_amount_xof, 0, ',', ' ') }} XOF</td>
                                        <td style="padding:8px 12px;">
                                            @if($c->status === 'paid')
                                                <span class="badge badge-green" style="font-size:10px;">Payée {{ $c->paid_at?->format('d/m/Y') }}</span>
                                            @else
                                                <span class="badge badge-yellow" style="font-size:10px;">En attente</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--muted);">
                        Aucun commissionnaire enregistré.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ═══ Modal Création ═══ -->
<div id="createModal" class="modal-backdrop hidden">
    <div class="modal-box" style="max-width:520px;">
        <div class="modal-header">
            <div>
                <div class="modal-title">Nouveau commissionnaire</div>
                <div class="modal-sub">Créer un compte partenaire avec pièce d'identité</div>
            </div>
            <button onclick="document.getElementById('createModal').classList.add('hidden')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:20px;">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('admin.commissionnaires.store') }}" enctype="multipart/form-data">
                @csrf
                <div style="display:flex;flex-direction:column;gap:14px;">

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Prénom *</label>
                            <input type="text" name="first_name" class="form-input" placeholder="Jean" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nom *</label>
                            <input type="text" name="last_name" class="form-input" placeholder="Dupont" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" placeholder="jean@exemple.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="phone" class="form-input" placeholder="+221 77 000 00 00">
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Mot de passe *</label>
                            <input type="password" name="password" class="form-input" placeholder="Min. 8 caractères" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirmer *</label>
                            <input type="password" name="password_confirmation" class="form-input" placeholder="Répéter" required>
                        </div>
                    </div>

                    <!-- Upload pièce d'identité -->
                    <div class="form-group">
                        <label class="form-label">Pièce justificative d'identité</label>
                        <div id="dropzone" onclick="document.getElementById('identityFile').click()"
                            style="border:2px dashed var(--border);border-radius:10px;padding:24px;text-align:center;cursor:pointer;transition:border-color .2s;"
                            ondragover="event.preventDefault();this.style.borderColor='#6366f1';"
                            ondragleave="this.style.borderColor='var(--border)'"
                            ondrop="handleDrop(event)">
                            <i class="fas fa-cloud-arrow-up" style="font-size:28px;color:var(--muted);margin-bottom:8px;display:block;"></i>
                            <div style="font-size:13px;color:#94a3b8;" id="dropText">
                                Cliquez ou glissez un fichier ici
                            </div>
                            <div style="font-size:11px;color:var(--muted);margin-top:4px;">PDF, JPG, PNG — max 5 Mo</div>
                        </div>
                        <input type="file" id="identityFile" name="identity_document" accept=".pdf,.jpg,.jpeg,.png" style="display:none;" onchange="updateDropzone(this)">
                    </div>

                    <div style="background:rgba(245,158,11,.05);border:1px solid rgba(245,158,11,.2);border-radius:8px;padding:12px;font-size:12px;color:#94a3b8;">
                        <i class="fas fa-shield-halved" style="color:#fbbf24;margin-right:6px;"></i>
                        La pièce d'identité est stockée de façon <strong style="color:#fbbf24;">sécurisée et privée</strong>. Elle ne sera accessible qu'aux administrateurs.
                    </div>

                    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:4px;">
                        <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="btn btn-outline">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Créer le compte
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleDetails(id) {
    const row = document.getElementById(id);
    row.classList.toggle('hidden');
}

function updateDropzone(input) {
    const file = input.files[0];
    if (file) {
        document.getElementById('dropText').innerHTML =
            '<i class="fas fa-file-check" style="color:#34d399;margin-right:6px;"></i>' +
            '<strong style="color:#34d399;">' + file.name + '</strong>' +
            ' (' + (file.size / 1024).toFixed(0) + ' Ko)';
        document.getElementById('dropzone').style.borderColor = '#34d399';
    }
}

function handleDrop(event) {
    event.preventDefault();
    document.getElementById('dropzone').style.borderColor = 'var(--border)';
    const file = event.dataTransfer.files[0];
    if (file) {
        const dt  = new DataTransfer();
        dt.items.add(file);
        document.getElementById('identityFile').files = dt.files;
        updateDropzone(document.getElementById('identityFile'));
    }
}
</script>
@endsection
