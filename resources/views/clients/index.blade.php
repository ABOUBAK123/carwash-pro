@extends('layouts.app')
@section('title', 'Clients')

@section('content')

<div class="page-header flex items-start justify-between">
    <div>
        <h1 class="page-title">Clients</h1>
        <p class="page-subtitle">{{ $clients->count() }} client(s) enregistré(s)</p>
    </div>
    <button onclick="toggleModal('modal-add')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau client
    </button>
</div>

<div class="card overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Véhicule</th>
                <th>Plaque</th>
                <th>Client depuis</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($clients as $c)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-sky-500/15 flex items-center justify-center text-xs font-bold text-sky-400 flex-shrink-0">
                            {{ strtoupper(substr($c->name,0,2)) }}
                        </div>
                        <p class="font-semibold text-slate-200">{{ $c->name }}</p>
                    </div>
                </td>
                <td><span class="text-sm text-slate-300">{{ $c->phone ?? '—' }}</span></td>
                <td><span class="text-sm text-slate-400">{{ $c->email ?? '—' }}</span></td>
                <td><span class="text-sm text-slate-300">{{ $c->vehicle_brand ?? '—' }}</span></td>
                <td>
                    @if($c->vehicle_plate)
                    <span class="font-mono text-xs bg-slate-800 text-slate-300 px-2.5 py-1 rounded-md border border-slate-700">{{ $c->vehicle_plate }}</span>
                    @else <span class="text-slate-600">—</span> @endif
                </td>
                <td><span class="text-sm text-slate-500">{{ $c->created_at->format('d/m/Y') }}</span></td>
                <td>
                    <form method="POST" action="{{ route('clients.destroy', $c) }}"
                          onsubmit="return confirm('Supprimer ce client ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-icon btn-outline">
                            <i class="fas fa-trash-can text-rose-400 text-xs"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-16 text-center">
                    <i class="fas fa-user-group text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucun client enregistré</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div id="modal-add" class="modal-backdrop hidden">
    <div class="modal-box max-w-md">
        <div class="modal-header">
            <div>
                <h2 class="text-lg font-bold text-white">Nouveau client</h2>
                <p class="text-xs text-slate-500 mt-0.5">Enregistrez les informations du client</p>
            </div>
            <button onclick="toggleModal('modal-add')" class="btn btn-icon btn-outline">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('clients.store') }}" class="space-y-4">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nom complet *</label>
                    <input type="text" name="name" required class="form-input" placeholder="Nom et prénom">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="phone" class="form-input" placeholder="+33 6 00 00 00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="email@ex.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marque véhicule</label>
                        <input type="text" name="vehicle_brand" class="form-input" placeholder="Renault, BMW…">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Immatriculation</label>
                        <input type="text" name="vehicle_plate" class="form-input" placeholder="AA-000-BB">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modal-add')" class="btn btn-outline flex-1 justify-center">Annuler</button>
                    <button type="submit" class="btn btn-primary flex-1 justify-center">
                        <i class="fas fa-user-plus"></i> Ajouter
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
