@extends('layouts.app')
@section('title', 'Services')

@section('content')

<div class="page-header flex items-start justify-between">
    <div>
        <h1 class="page-title">Services</h1>
        <p class="page-subtitle">{{ $services->count() }} prestation(s) configurée(s)</p>
    </div>
    <button onclick="toggleModal('modal-add')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau service
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
    @forelse($services as $svc)
    <div class="card p-5 card-hover transition-all {{ !$svc->is_active ? 'opacity-50' : '' }}">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/15 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-star text-amber-400 text-sm"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-200">{{ $svc->name }}</p>
                    @if($svc->description)
                    <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $svc->description }}</p>
                    @endif
                </div>
            </div>
            <span class="badge {{ $svc->is_active ? 'badge-green' : 'badge-red' }} flex-shrink-0">
                {{ $svc->is_active ? 'Actif' : 'Inactif' }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="bg-[#0d0f1a] rounded-xl p-3 text-center border border-[#1e2235]">
                <p class="text-2xl font-extrabold text-emerald-400">{{ number_format($svc->price, 2) }}€</p>
                <p class="text-xs text-slate-600 mt-0.5">Prix</p>
            </div>
            <div class="bg-[#0d0f1a] rounded-xl p-3 text-center border border-[#1e2235]">
                <p class="text-2xl font-extrabold text-indigo-400">{{ $svc->duration }}<span class="text-sm font-normal text-slate-500"> min</span></p>
                <p class="text-xs text-slate-600 mt-0.5">Durée</p>
            </div>
        </div>

        <div class="flex gap-2">
            <form method="POST" action="{{ route('services.toggle', $svc) }}" class="flex-1">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $svc->is_active ? 'btn-danger' : 'btn-success' }} w-full justify-center">
                    {{ $svc->is_active ? 'Désactiver' : 'Activer' }}
                </button>
            </form>
            <form method="POST" action="{{ route('services.destroy', $svc) }}"
                  onsubmit="return confirm('Supprimer ce service ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-icon btn-outline">
                    <i class="fas fa-trash-can text-rose-400 text-xs"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 card p-16 text-center">
        <i class="fas fa-star text-slate-700 text-5xl mb-4"></i>
        <p class="text-slate-500 font-medium">Aucun service configuré</p>
        <p class="text-slate-600 text-sm mt-1">Ajoutez vos prestations pour commencer à facturer</p>
        <button onclick="toggleModal('modal-add')" class="btn btn-primary mt-5 mx-auto">
            <i class="fas fa-plus"></i> Ajouter un service
        </button>
    </div>
    @endforelse
</div>

<!-- MODAL AJOUTER -->
<div id="modal-add" class="modal-backdrop hidden">
    <div class="modal-box max-w-md">
        <div class="modal-header">
            <div>
                <h2 class="text-lg font-bold text-white">Nouveau service</h2>
                <p class="text-xs text-slate-500 mt-0.5">Configurez une nouvelle prestation</p>
            </div>
            <button onclick="toggleModal('modal-add')" class="btn btn-icon btn-outline">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('services.store') }}" class="space-y-4">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nom du service *</label>
                    <input type="text" name="name" required class="form-input" placeholder="Ex: Lavage Intérieur + Extérieur">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-input" placeholder="Décrivez les détails de ce service…" rows="2"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Prix (€) *</label>
                        <input type="number" name="price" step="0.01" min="0" required class="form-input" placeholder="20.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durée (minutes) *</label>
                        <input type="number" name="duration" min="1" required class="form-input" placeholder="45">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modal-add')" class="btn btn-outline flex-1 justify-center">Annuler</button>
                    <button type="submit" class="btn btn-primary flex-1 justify-center">
                        <i class="fas fa-plus"></i> Créer
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
