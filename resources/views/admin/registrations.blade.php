@extends('layouts.app')

@section('title', 'Inscriptions — CarWash Pro')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Demandes d'inscription</div>
        <div class="page-sub">Gestion des nouvelles demandes d'ouverture de centre</div>
    </div>
</div>

@if(session('error'))
<div class="alert alert-error fade-up">
    <i class="fas fa-circle-exclamation" style="flex-shrink:0;margin-top:1px;"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

<!-- KPIs -->
<div class="grid-4" style="margin-bottom:24px;">
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Total</div>
        <div style="font-size:28px;font-weight:800;color:#f1f5f9;">{{ $counts['total'] }}</div>
    </div>
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">En attente</div>
        <div style="font-size:28px;font-weight:800;color:#fbbf24;">{{ $counts['pending'] }}</div>
    </div>
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Approuvées</div>
        <div style="font-size:28px;font-weight:800;color:#34d399;">{{ $counts['approved'] }}</div>
    </div>
    <div class="stat-card">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;">Rejetées</div>
        <div style="font-size:28px;font-weight:800;color:#fb7185;">{{ $counts['rejected'] }}</div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div style="padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:14px;font-weight:600;color:#f1f5f9;">Toutes les demandes</span>
        <span class="badge badge-yellow">{{ $counts['pending'] }} en attente</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Centre / Propriétaire</th>
                    <th>Contact</th>
                    <th>Ville</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $reg)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#f1f5f9;">{{ $reg->center_name }}</div>
                        <div style="font-size:12px;color:var(--muted);">{{ $reg->owner_name }}</div>
                    </td>
                    <td>
                        <div>{{ $reg->email }}</div>
                        <div style="font-size:12px;color:var(--muted);">{{ $reg->phone }}</div>
                    </td>
                    <td>{{ $reg->city }}</td>
                    <td style="font-size:12px;color:var(--muted);">{{ $reg->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge {{ \App\Models\RegistrationRequest::$statusBadges[$reg->status] }}">
                            {{ \App\Models\RegistrationRequest::$statusLabels[$reg->status] }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center;">
                            <button onclick="openDetail({{ $reg->id }})" class="btn btn-outline btn-sm btn-icon" title="Détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($reg->isPending())
                            <form method="POST" action="{{ route('admin.registrations.approve', $reg) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approuver et créer le centre ?')">
                                    <i class="fas fa-check"></i> Approuver
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.registrations.reject', $reg) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Rejeter cette demande ?')">
                                    <i class="fas fa-times"></i> Rejeter
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.registrations.destroy', $reg) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm btn-icon" onclick="return confirm('Supprimer ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Detail panel (hidden) -->
                <tr id="detail-{{ $reg->id }}" class="hidden">
                    <td colspan="6" style="background:var(--bg);padding:20px 24px;">
                        <div class="grid-3" style="gap:20px;">
                            <div>
                                <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;margin-bottom:6px;">Adresse</div>
                                <div style="font-size:13px;">{{ $reg->address }}, {{ $reg->city }}</div>
                            </div>
                            @if($reg->description)
                            <div>
                                <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;margin-bottom:6px;">Description</div>
                                <div style="font-size:13px;">{{ $reg->description }}</div>
                            </div>
                            @endif
                            @if($reg->services)
                            <div>
                                <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;margin-bottom:6px;">Services souhaités</div>
                                <div style="font-size:13px;">{{ $reg->services }}</div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted);">Aucune demande d'inscription</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function openDetail(id) {
    const row = document.getElementById('detail-' + id);
    row.classList.toggle('hidden');
}
</script>
@endsection
