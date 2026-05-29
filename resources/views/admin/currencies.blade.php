@extends('layouts.app')

@section('title', 'Devises — CarWash Pro')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">Devises</div>
        <div class="page-sub">Gestion des devises et taux de change</div>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter une devise
    </button>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Symbole</th>
                    <th>Taux (vs EUR)</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($currencies as $currency)
                <tr>
                    <td><span class="badge badge-blue text-mono">{{ $currency->code }}</span></td>
                    <td style="font-weight:500;color:#f1f5f9;">{{ $currency->name }}</td>
                    <td style="font-size:16px;font-weight:600;">{{ $currency->symbol }}</td>
                    <td class="text-mono">{{ number_format($currency->rate, 6) }}</td>
                    <td>
                        @if($currency->is_active)
                            <span class="badge badge-green">Active</span>
                        @else
                            <span class="badge badge-gray">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button onclick="openEdit({{ $currency->id }}, '{{ $currency->name }}', '{{ $currency->symbol }}', '{{ $currency->rate }}')" class="btn btn-outline btn-sm btn-icon">
                                <i class="fas fa-pen"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.currencies.toggle', $currency) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-{{ $currency->is_active ? 'warning' : 'success' }} btn-sm btn-icon" title="{{ $currency->is_active ? 'Désactiver' : 'Activer' }}">
                                    <i class="fas fa-{{ $currency->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.currencies.destroy', $currency) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Supprimer ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted);">Aucune devise configurée</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal-backdrop hidden">
    <div class="modal-box" style="max-width:440px;">
        <div class="modal-header">
            <div>
                <div class="modal-title">Ajouter une devise</div>
                <div class="modal-sub">Nouvelle devise et taux de change</div>
            </div>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('admin.currencies.store') }}">
                @csrf
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Code ISO *</label>
                            <input type="text" name="code" class="form-input" placeholder="EUR" maxlength="5" style="text-transform:uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Symbole *</label>
                            <input type="text" name="symbol" class="form-input" placeholder="€" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom complet *</label>
                        <input type="text" name="name" class="form-input" placeholder="Euro" maxlength="80" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Taux de change *</label>
                        <input type="number" name="rate" class="form-input" placeholder="1.000000" step="0.000001" min="0.000001" required>
                        <span style="font-size:11px;color:var(--muted);">Taux par rapport à la devise de référence (EUR)</span>
                    </div>
                    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:6px;">
                        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="btn btn-outline">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-backdrop hidden">
    <div class="modal-box" style="max-width:440px;">
        <div class="modal-header">
            <div>
                <div class="modal-title">Modifier la devise</div>
            </div>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:18px;">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editForm">
                @csrf @method('PATCH')
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div class="form-group">
                        <label class="form-label">Nom complet *</label>
                        <input type="text" name="name" id="editName" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Symbole *</label>
                        <input type="text" name="symbol" id="editSymbol" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Taux de change *</label>
                        <input type="number" name="rate" id="editRate" class="form-input" step="0.000001" min="0.000001" required>
                    </div>
                    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:6px;">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="btn btn-outline">Annuler</button>
                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEdit(id, name, symbol, rate) {
    document.getElementById('editForm').action = '/admin/currencies/' + id;
    document.getElementById('editName').value   = name;
    document.getElementById('editSymbol').value = symbol;
    document.getElementById('editRate').value   = rate;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection
