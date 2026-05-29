@extends('layouts.app')
@section('title', 'Centres de lavage')

@section('content')

<div class="page-header flex items-start justify-between">
    <div>
        <h1 class="page-title">Centres de lavage</h1>
        <p class="page-subtitle">{{ $carwashes->count() }} centre(s) enregistré(s)</p>
    </div>
    <button onclick="toggleModal('modal-create')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau centre
    </button>
</div>

<div class="card overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Localisation</th>
                <th>Contact</th>
                <th>Manager</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($carwashes as $cw)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-500/15 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-building text-indigo-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-200">{{ $cw->name }}</p>
                            <p class="text-xs text-slate-500">{{ $cw->address }}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="text-slate-300">{{ $cw->city }}</p>
                    @if($cw->postal_code)<p class="text-xs text-slate-500">{{ $cw->postal_code }}</p>@endif
                </td>
                <td>
                    <p class="text-slate-400 text-sm">{{ $cw->phone ?? '—' }}</p>
                    @if($cw->email)<p class="text-xs text-slate-500">{{ $cw->email }}</p>@endif
                </td>
                <td>
                    @if($cw->manager)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-violet-500/20 flex items-center justify-center text-xs font-bold text-violet-400">
                            {{ strtoupper(substr($cw->manager->first_name,0,1)) }}
                        </div>
                        <span class="text-sm text-slate-300">{{ $cw->manager->full_name }}</span>
                    </div>
                    @else
                    <span class="text-slate-600 text-sm">Non assigné</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $cw->is_active ? 'badge-green' : 'badge-red' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $cw->is_active ? 'bg-emerald-400' : 'bg-rose-400' }} mr-1.5"></span>
                        {{ $cw->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('admin.carwashes.toggle', $cw) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $cw->is_active ? 'btn-danger' : 'btn-success' }}">
                                {{ $cw->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.carwashes.delete', $cw) }}"
                              onsubmit="return confirm('Supprimer « {{ $cw->name }} » ? Cette action est irréversible.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-icon btn-outline">
                                <i class="fas fa-trash-can text-rose-400 text-xs"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <i class="fas fa-building text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucun centre de lavage enregistré</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ── MODAL CRÉER ── -->
<div id="modal-create" class="modal-backdrop hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <div>
                <h2 class="text-lg font-bold text-white">Nouveau centre de lavage</h2>
                <p class="text-xs text-slate-500 mt-0.5">Remplissez les informations du centre</p>
            </div>
            <button onclick="toggleModal('modal-create')" class="btn btn-icon btn-outline">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('admin.carwashes.store') }}" class="space-y-4">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nom du centre *</label>
                    <input type="text" name="name" required class="form-input" placeholder="Ex: AutoSplash Premium">
                </div>
                <div class="form-group">
                    <label class="form-label">Adresse complète *</label>
                    <input type="text" name="address" required class="form-input" placeholder="15 Avenue de la Paix">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Ville *</label>
                        <input type="text" name="city" required class="form-input" placeholder="Paris">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Code postal</label>
                        <input type="text" name="postal_code" class="form-input" placeholder="75001">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="phone" class="form-input" placeholder="+33 1 23 45 67 89">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="contact@centre.fr">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modal-create')" class="btn btn-outline flex-1 justify-center">Annuler</button>
                    <button type="submit" class="btn btn-primary flex-1 justify-center">
                        <i class="fas fa-plus"></i> Créer le centre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(id) {
    document.getElementById(id).classList.toggle('hidden');
}
</script>
@endsection
