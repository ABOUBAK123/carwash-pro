@extends('layouts.app')
@section('title', 'Équipements')

@section('content')

@php
$typeLabels   = \App\Models\Equipment::$typeLabels;
$statusLabels = \App\Models\Equipment::$statusLabels;
$statusBadges = \App\Models\Equipment::$statusBadges;
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Équipements</h1>
        <p class="page-subtitle">{{ $stats['total'] }} équipement(s) enregistré(s)</p>
    </div>
    <button onclick="toggleModal('modal-add')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter
    </button>
</div>

<!-- KPIs -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-indigo-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-toolbox text-indigo-400"></i>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-white">{{ $stats['total'] }}</p>
            <p class="text-xs text-slate-500">Total</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-circle-check text-emerald-400"></i>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-emerald-400">{{ $stats['available'] }}</p>
            <p class="text-xs text-slate-500">Disponibles</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-amber-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-wrench text-amber-400"></i>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-amber-400">{{ $stats['maintenance'] }}</p>
            <p class="text-xs text-slate-500">En maintenance</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4">
        <div class="w-10 h-10 rounded-xl bg-rose-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-triangle-exclamation text-rose-400"></i>
        </div>
        <div>
            <p class="text-2xl font-extrabold text-rose-400">{{ $stats['broken'] }}</p>
            <p class="text-xs text-slate-500">Hors service</p>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Équipement</th>
                <th>Type</th>
                <th>Achat</th>
                <th>Coût</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipment as $eq)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-500/15 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-toolbox text-indigo-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-200">{{ $eq->name }}</p>
                            @if($eq->notes)
                            <p class="text-xs text-slate-500 mt-0.5">{{ Str::limit($eq->notes, 40) }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td><span class="text-sm text-slate-400">{{ $typeLabels[$eq->type] ?? $eq->type }}</span></td>
                <td>
                    @if($eq->purchase_date)
                    <span class="text-sm text-slate-400">{{ $eq->purchase_date->format('d/m/Y') }}</span>
                    @else
                    <span class="text-slate-600">—</span>
                    @endif
                </td>
                <td>
                    @if($eq->cost)
                    <span class="font-semibold text-indigo-300">{{ number_format($eq->cost, 2) }}€</span>
                    @else
                    <span class="text-slate-600">—</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $statusBadges[$eq->status] ?? 'badge-gray' }}">{{ $statusLabels[$eq->status] ?? $eq->status }}</span>
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <!-- Changer statut -->
                        <form method="POST" action="{{ route('equipment.status', $eq) }}" class="flex items-center gap-1.5">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="form-input text-xs py-1 px-2" style="width:auto">
                                @foreach($statusLabels as $k => $l)
                                <option value="{{ $k }}" {{ $eq->status===$k?'selected':'' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </form>
                        <form method="POST" action="{{ route('equipment.destroy', $eq) }}"
                              onsubmit="return confirm('Supprimer cet équipement ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-icon btn-outline">
                                <i class="fas fa-trash-can text-rose-400 text-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <i class="fas fa-toolbox text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucun équipement enregistré</p>
                    <button onclick="toggleModal('modal-add')" class="btn btn-primary mt-4 mx-auto">
                        <i class="fas fa-plus"></i> Ajouter un équipement
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL AJOUTER -->
<div id="modal-add" class="modal-backdrop hidden">
    <div class="modal-box" style="max-width:480px">
        <div class="modal-header">
            <div>
                <h2 class="modal-title">Nouvel équipement</h2>
                <p class="modal-sub">Enregistrez un équipement du centre</p>
            </div>
            <button onclick="toggleModal('modal-add')" class="btn btn-icon btn-outline">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('equipment.store') }}" class="space-y-4">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="name" required class="form-input" placeholder="Ex: Machine à laver principale">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select name="type" required class="form-input">
                            @foreach($typeLabels as $k => $l)
                            <option value="{{ $k }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Statut *</label>
                        <select name="status" required class="form-input">
                            @foreach($statusLabels as $k => $l)
                            <option value="{{ $k }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date d'achat</label>
                        <input type="date" name="purchase_date" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Coût (€)</label>
                        <input type="number" name="cost" step="0.01" min="0" class="form-input" placeholder="0.00">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-input" rows="2" placeholder="Informations complémentaires…"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modal-add')" class="btn btn-outline flex-1 justify-center">Annuler</button>
                    <button type="submit" class="btn btn-primary flex-1 justify-center">
                        <i class="fas fa-plus"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
</script>
@endsection
