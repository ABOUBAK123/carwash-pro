@extends('layouts.app')
@section('title', 'Rendez-vous')

@section('content')

<div class="page-header flex items-start justify-between">
    <div>
        <h1 class="page-title">Rendez-vous</h1>
        <p class="page-subtitle">{{ $appointments->count() }} rendez-vous au total</p>
    </div>
    <button onclick="toggleModal('modal-add')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau RDV
    </button>
</div>

<!-- Filter pills -->
<div class="flex gap-2 mb-6 flex-wrap">
    @foreach(['all'=>'Tous','scheduled'=>'Programmés','in_progress'=>'En cours','completed'=>'Terminés','cancelled'=>'Annulés'] as $key=>$label)
    <button onclick="filterApts('{{ $key }}')" id="fpill-{{ $key }}"
        class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all border
               {{ $key==='all' ? 'bg-indigo-500/15 border-indigo-500/30 text-indigo-300' : 'bg-transparent border-[#1e2235] text-slate-500 hover:border-slate-600 hover:text-slate-300' }}">
        {{ $label }}
    </button>
    @endforeach
</div>

<div class="card overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>Véhicule</th>
                <th>Service</th>
                <th>Laveur</th>
                <th>Date & Heure</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="apt-tbody">
            @forelse($appointments as $apt)
            @php
            $sc = ['scheduled'=>'badge-blue','in_progress'=>'badge-yellow','completed'=>'badge-green','cancelled'=>'badge-red'];
            @endphp
            <tr class="apt-row" data-status="{{ $apt->status }}">
                <td>
                    <p class="font-semibold text-slate-200">{{ $apt->client_name }}</p>
                    <p class="text-xs text-slate-500">{{ $apt->client_phone ?? '—' }}</p>
                </td>
                <td>
                    <p class="text-sm text-slate-300">{{ $apt->vehicle_brand ?? '—' }}</p>
                    @if($apt->vehicle_plate)
                    <span class="font-mono text-xs bg-slate-800 text-slate-400 px-2 py-0.5 rounded border border-slate-700">{{ $apt->vehicle_plate }}</span>
                    @endif
                </td>
                <td><span class="text-sm text-slate-400">{{ $apt->service_name ?? '—' }}</span></td>
                <td>
                    @if($apt->employee)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center text-xs font-bold text-teal-400">
                            {{ strtoupper(substr($apt->employee->first_name,0,1)) }}
                        </div>
                        <span class="text-sm text-slate-300">{{ $apt->employee->full_name }}</span>
                    </div>
                    @else <span class="text-slate-600 text-sm">—</span> @endif
                </td>
                <td>
                    <p class="text-sm text-slate-300">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('d/m/Y') }}</p>
                    <p class="text-xs text-slate-500">{{ substr($apt->appointment_time,0,5) }}</p>
                </td>
                <td>
                    <span class="badge {{ $sc[$apt->status] ?? 'badge-gray' }}">{{ $apt->status_label }}</span>
                </td>
                <td>
                    <div class="flex items-center gap-1.5">
                        @if($apt->status === 'scheduled')
                        <form method="POST" action="{{ route('appointments.start', $apt) }}">
                            @csrf @method('PATCH')
                            <button type="submit" title="Démarrer" class="btn btn-sm btn-warning btn-icon">
                                <i class="fas fa-play text-xs"></i>
                            </button>
                        </form>
                        @endif
                        @if($apt->status === 'in_progress')
                        <form method="POST" action="{{ route('appointments.complete', $apt) }}">
                            @csrf @method('PATCH')
                            <button type="submit" title="Terminer" class="btn btn-sm btn-success btn-icon">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                        </form>
                        @endif
                        @if(in_array($apt->status, ['scheduled','in_progress']))
                        <form method="POST" action="{{ route('appointments.cancel', $apt) }}">
                            @csrf @method('PATCH')
                            <button type="submit" title="Annuler" class="btn btn-sm btn-danger btn-icon">
                                <i class="fas fa-ban text-xs"></i>
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('appointments.destroy', $apt) }}"
                              onsubmit="return confirm('Supprimer ce rendez-vous ?')">
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
                <td colspan="7" class="py-16 text-center">
                    <i class="fas fa-calendar-xmark text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucun rendez-vous</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL NOUVEAU RDV -->
<div id="modal-add" class="modal-backdrop hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <div>
                <h2 class="text-lg font-bold text-white">Nouveau rendez-vous</h2>
                <p class="text-xs text-slate-500 mt-0.5">Programmez une prestation</p>
            </div>
            <button onclick="toggleModal('modal-add')" class="btn btn-icon btn-outline">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group col-span-2">
                        <label class="form-label">Nom du client *</label>
                        <input type="text" name="client_name" required class="form-input" placeholder="Nom complet">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="client_phone" class="form-input" placeholder="+33 6 00 00 00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marque véhicule</label>
                        <input type="text" name="vehicle_brand" class="form-input" placeholder="BMW, Renault…">
                    </div>
                    <div class="form-group col-span-2">
                        <label class="form-label">Immatriculation</label>
                        <input type="text" name="vehicle_plate" class="form-input" placeholder="AA-000-BB">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service</label>
                        <select name="service_id" class="form-input">
                            <option value="">— Sélectionner —</option>
                            @foreach($services as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->price }}€)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Laveur</label>
                        <select name="employee_id" class="form-input">
                            <option value="">— Sélectionner —</option>
                            @foreach($employees as $e)
                            <option value="{{ $e->id }}">{{ $e->code }} — {{ $e->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" name="appointment_date" required value="{{ date('Y-m-d') }}" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Heure *</label>
                        <input type="time" name="appointment_time" required value="{{ date('H:i') }}" class="form-input">
                    </div>
                    <div class="form-group col-span-2">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-input" placeholder="Informations supplémentaires…" rows="2"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modal-add')" class="btn btn-outline flex-1 justify-center">Annuler</button>
                    <button type="submit" class="btn btn-primary flex-1 justify-center">
                        <i class="fas fa-calendar-plus"></i> Créer le RDV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
function filterApts(status) {
    document.querySelectorAll('.apt-row').forEach(r => {
        r.style.display = (status === 'all' || r.dataset.status === status) ? '' : 'none';
    });
    document.querySelectorAll('[id^="fpill-"]').forEach(btn => {
        const active = btn.id === 'fpill-' + status;
        btn.className = btn.className
            .replace(/bg-indigo-500\/15|border-indigo-500\/30|text-indigo-300|bg-transparent|border-\[#1e2235\]|text-slate-500|hover:border-slate-600|hover:text-slate-300/g,'').trim()
            + (active ? ' bg-indigo-500/15 border-indigo-500/30 text-indigo-300' : ' bg-transparent border-[#1e2235] text-slate-500 hover:border-slate-600 hover:text-slate-300');
    });
}
</script>
@endsection
