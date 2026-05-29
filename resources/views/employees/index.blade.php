@extends('layouts.app')
@section('title', 'Employés')

@section('content')

<div class="page-header flex items-start justify-between">
    <div>
        <h1 class="page-title">Employés</h1>
        <p class="page-subtitle">{{ $employees->count() }} employé(s) dans ce centre</p>
    </div>
    <button onclick="toggleModal('modal-add')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel employé
    </button>
</div>

<div class="card overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Employé</th>
                <th>Contact</th>
                <th>Rémunération</th>
                <th>Performance</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $emp)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                            {{ strtoupper(substr($emp->first_name,0,1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-200">{{ $emp->full_name }}</p>
                            <p class="text-xs font-mono text-indigo-400">{{ $emp->code }}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="text-sm text-slate-300">{{ $emp->phone ?? '—' }}</p>
                    @if($emp->email)<p class="text-xs text-slate-500">{{ $emp->email }}</p>@endif
                </td>
                <td>
                    @php
                    $type = match($emp->salary_type) {
                        'hourly'     => ['label'=>'Horaire · '.$emp->hourly_rate.'€/h', 'badge'=>'badge-blue'],
                        'fixed'      => ['label'=>'Fixe · '.number_format($emp->fixed_salary,0).'€/mois', 'badge'=>'badge-purple'],
                        'commission' => ['label'=>'Commission · '.$emp->commission_rate.'%', 'badge'=>'badge-yellow'],
                        default      => ['label'=>$emp->salary_type, 'badge'=>'badge-gray'],
                    };
                    @endphp
                    <span class="badge {{ $type['badge'] }}">{{ $type['label'] }}</span>
                </td>
                <td>
                    <div class="space-y-0.5">
                        <p class="text-sm font-semibold text-slate-200">{{ $emp->total_cars_washed }} <span class="text-xs font-normal text-slate-500">lavages</span></p>
                        <p class="text-sm font-bold text-emerald-400">{{ number_format($emp->total_earnings, 2) }}€ <span class="text-xs font-normal text-slate-500">gagnés</span></p>
                    </div>
                </td>
                <td>
                    <span class="badge {{ $emp->is_active ? 'badge-green' : 'badge-red' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $emp->is_active ? 'bg-emerald-400' : 'bg-rose-400' }} mr-1.5"></span>
                        {{ $emp->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('employees.toggle', $emp) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $emp->is_active ? 'btn-danger' : 'btn-success' }}">
                                {{ $emp->is_active ? 'Suspendre' : 'Activer' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('employees.destroy', $emp) }}"
                              onsubmit="return confirm('Supprimer cet employé ?')">
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
                    <i class="fas fa-id-badge text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucun employé enregistré</p>
                    <button onclick="toggleModal('modal-add')" class="btn btn-primary mt-4 mx-auto">
                        <i class="fas fa-plus"></i> Ajouter un employé
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL AJOUTER -->
<div id="modal-add" class="modal-backdrop hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <div>
                <h2 class="text-lg font-bold text-white">Nouvel employé</h2>
                <p class="text-xs text-slate-500 mt-0.5">Ajoutez un laveur à votre équipe</p>
            </div>
            <button onclick="toggleModal('modal-add')" class="btn btn-icon btn-outline">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('employees.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input type="text" name="first_name" required class="form-input" placeholder="Prénom">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="last_name" required class="form-input" placeholder="Nom">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone *</label>
                        <input type="tel" name="phone" required class="form-input" placeholder="+33 6 00 00 00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="emp@email.com">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Type de rémunération</label>
                    <select name="salary_type" id="salary_type" onchange="updateSalaryFields()" class="form-input">
                        <option value="commission">Commission par service</option>
                        <option value="hourly">Salaire horaire</option>
                        <option value="fixed">Salaire fixe mensuel</option>
                    </select>
                </div>

                <div id="field_commission" class="form-group">
                    <label class="form-label">Taux de commission (%)</label>
                    <input type="number" name="commission_rate" value="30" min="0" max="100" step="0.5" class="form-input">
                </div>
                <div id="field_hourly" class="form-group hidden">
                    <label class="form-label">Taux horaire (€/h)</label>
                    <input type="number" name="hourly_rate" step="0.01" min="0" class="form-input" placeholder="Ex: 12.50">
                </div>
                <div id="field_fixed" class="form-group hidden">
                    <label class="form-label">Salaire mensuel fixe (€)</label>
                    <input type="number" name="fixed_salary" step="0.01" min="0" class="form-input" placeholder="Ex: 1500">
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modal-add')" class="btn btn-outline flex-1 justify-center">Annuler</button>
                    <button type="submit" class="btn btn-primary flex-1 justify-center">
                        <i class="fas fa-user-plus"></i> Créer l'employé
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
function updateSalaryFields() {
    const type = document.getElementById('salary_type').value;
    document.getElementById('field_commission').classList.toggle('hidden', type !== 'commission');
    document.getElementById('field_hourly').classList.toggle('hidden', type !== 'hourly');
    document.getElementById('field_fixed').classList.toggle('hidden', type !== 'fixed');
}
</script>
@endsection
