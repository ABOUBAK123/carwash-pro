@extends('layouts.app')
@section('title', 'Dépenses')

@section('content')

@php
$periodLabels = ['week'=>'Cette semaine','month'=>'Ce mois','quarter'=>'Ce trimestre','year'=>'Cette année'];
$typeLabels   = \App\Models\Expense::$typeLabels;
$typeBadges   = \App\Models\Expense::$typeBadges;
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Dépenses</h1>
        <p class="page-subtitle">{{ $periodLabels[$period] ?? $period }} — {{ $expenses->count() }} entrée(s)</p>
    </div>
    <button onclick="toggleModal('modal-add')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle dépense
    </button>
</div>

<!-- Filtres -->
<div class="flex flex-wrap gap-3 mb-6">
    <form method="GET" class="flex flex-wrap gap-2">
        <select name="period" onchange="this.form.submit()" class="form-input text-sm py-2" style="width:auto">
            @foreach($periodLabels as $k => $l)
            <option value="{{ $k }}" {{ $period===$k?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
        <select name="type" onchange="this.form.submit()" class="form-input text-sm py-2" style="width:auto">
            <option value="all" {{ $typeFilter==='all'?'selected':'' }}>Tous les types</option>
            @foreach($typeLabels as $k => $l)
            <option value="{{ $k }}" {{ $typeFilter===$k?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
    </form>
</div>

<!-- KPI par type -->
<div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 mb-6">
    @foreach($typeLabels as $type => $label)
    @php $row = $byType[$type] ?? null; @endphp
    <div class="stat-card text-center p-4">
        <span class="badge {{ $typeBadges[$type] }} mb-2">{{ $label }}</span>
        <p class="text-lg font-extrabold text-white mt-1">{{ $row ? number_format($row->total, 2) : '0.00' }}€</p>
        <p class="text-xs text-slate-600 mt-0.5">{{ $row->cnt ?? 0 }} entrée(s)</p>
    </div>
    @endforeach
</div>

<!-- Total -->
<div class="card p-4 mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-rose-500/15 flex items-center justify-center">
            <i class="fas fa-chart-pie text-rose-400"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Total des dépenses</p>
            <p class="text-2xl font-extrabold text-rose-400">{{ number_format($total, 2) }}€</p>
        </div>
    </div>
    <span class="badge badge-red">{{ $periodLabels[$period] ?? $period }}</span>
</div>

<!-- Table -->
<div class="card overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th class="text-right">Montant</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $exp)
            <tr>
                <td>
                    <p class="text-sm text-slate-300">{{ $exp->expense_date->format('d/m/Y') }}</p>
                    <p class="text-xs text-slate-500">{{ $exp->expense_date->locale('fr')->isoFormat('dddd') }}</p>
                </td>
                <td>
                    <span class="badge {{ $typeBadges[$exp->type] ?? 'badge-gray' }}">{{ $typeLabels[$exp->type] ?? $exp->type }}</span>
                </td>
                <td>
                    <p class="text-sm text-slate-400">{{ $exp->description ?? '—' }}</p>
                </td>
                <td class="text-right">
                    <span class="text-lg font-bold text-rose-400">{{ number_format($exp->amount, 2) }}€</span>
                </td>
                <td>
                    <form method="POST" action="{{ route('expenses.destroy', $exp) }}"
                          onsubmit="return confirm('Supprimer cette dépense ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-icon btn-outline">
                            <i class="fas fa-trash-can text-rose-400 text-xs"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-16 text-center">
                    <i class="fas fa-receipt text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucune dépense sur cette période</p>
                    <button onclick="toggleModal('modal-add')" class="btn btn-primary mt-4 mx-auto">
                        <i class="fas fa-plus"></i> Ajouter
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL NOUVELLE DÉPENSE -->
<div id="modal-add" class="modal-backdrop hidden">
    <div class="modal-box" style="max-width:460px">
        <div class="modal-header">
            <div>
                <h2 class="modal-title">Nouvelle dépense</h2>
                <p class="modal-sub">Enregistrez une charge opérationnelle</p>
            </div>
            <button onclick="toggleModal('modal-add')" class="btn btn-icon btn-outline">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('expenses.store') }}" class="space-y-4">
                @csrf
                <div class="form-group">
                    <label class="form-label">Type de dépense *</label>
                    <select name="type" required class="form-input">
                        @foreach($typeLabels as $k => $l)
                        <option value="{{ $k }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Montant (€) *</label>
                        <input type="number" name="amount" step="0.01" min="0.01" required class="form-input" placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-input" placeholder="Détails de la dépense…">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modal-add')" class="btn btn-outline flex-1 justify-center">Annuler</button>
                    <button type="submit" class="btn btn-primary flex-1 justify-center">
                        <i class="fas fa-check"></i> Enregistrer
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
