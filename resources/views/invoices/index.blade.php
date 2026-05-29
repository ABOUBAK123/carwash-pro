@extends('layouts.app')
@section('title', 'Facturation')

@section('content')

<div class="page-header flex items-start justify-between">
    <div>
        <h1 class="page-title">Facturation</h1>
        <p class="page-subtitle">{{ $invoices->count() }} facture(s) émise(s)</p>
    </div>
    <button onclick="toggleModal('modal-new')" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle facture
    </button>
</div>

<!-- Stats -->
@php
$totalToday = $invoices->filter(fn($i)=>$i->created_at->isToday())->sum('total_amount');
$totalMonth = $invoices->filter(fn($i)=>$i->created_at->isCurrentMonth())->sum('total_amount');
$totalAll   = $invoices->sum('total_amount');
@endphp
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-7">
    <div class="stat-card flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-sky-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-calendar-day text-sky-400"></i>
        </div>
        <div>
            <p class="text-xl font-extrabold text-white">{{ number_format($totalToday,2) }}€</p>
            <p class="text-xs text-slate-500">Aujourd'hui</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-violet-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-chart-column text-violet-400"></i>
        </div>
        <div>
            <p class="text-xl font-extrabold text-white">{{ number_format($totalMonth,2) }}€</p>
            <p class="text-xs text-slate-500">Ce mois-ci</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-coins text-emerald-400"></i>
        </div>
        <div>
            <p class="text-xl font-extrabold text-white">{{ number_format($totalAll,2) }}€</p>
            <p class="text-xs text-slate-500">Total encaissé</p>
        </div>
    </div>
</div>

<div class="card overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>N° Facture</th>
                <th>Véhicule</th>
                <th>Service</th>
                <th>Laveur</th>
                <th>Commission</th>
                <th>Montant</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $inv)
            <tr>
                <td>
                    <span class="font-mono text-indigo-400 font-semibold text-sm">{{ $inv->invoice_number }}</span>
                </td>
                <td>
                    <p class="text-sm text-slate-300">{{ $inv->vehicle_brand }}</p>
                    <span class="font-mono text-xs bg-slate-800 text-slate-400 px-2 py-0.5 rounded border border-slate-700">{{ $inv->vehicle_plate }}</span>
                </td>
                <td><span class="text-sm text-slate-400">{{ $inv->service_name ?? '—' }}</span></td>
                <td>
                    @if($inv->employee)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center text-xs font-bold text-teal-400">
                            {{ strtoupper(substr($inv->employee->first_name,0,1)) }}
                        </div>
                        <span class="text-sm text-slate-300">{{ $inv->employee->full_name }}</span>
                    </div>
                    @else <span class="text-slate-600 text-sm">—</span> @endif
                </td>
                <td><span class="text-amber-400 font-semibold text-sm">{{ number_format($inv->employee_commission, 2) }}€</span></td>
                <td><span class="text-emerald-400 font-bold text-base">{{ number_format($inv->total_amount, 2) }}€</span></td>
                <td>
                    <p class="text-sm text-slate-400">{{ $inv->created_at->format('d/m/Y') }}</p>
                    <p class="text-xs text-slate-600">{{ $inv->created_at->format('H:i') }}</p>
                </td>
                <td>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-icon btn-outline" title="Voir">
                            <i class="fas fa-eye text-indigo-400 text-xs"></i>
                        </a>
                        <form method="POST" action="{{ route('invoices.destroy', $inv) }}"
                              onsubmit="return confirm('Supprimer cette facture ?')">
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
                <td colspan="8" class="py-16 text-center">
                    <i class="fas fa-file-invoice text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucune facture émise</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL NOUVELLE FACTURE -->
<div id="modal-new" class="modal-backdrop hidden">
    <div class="modal-box max-w-lg">
        <div class="modal-header">
            <div>
                <h2 class="text-lg font-bold text-white">Nouvelle facturation</h2>
                <p class="text-xs text-slate-500 mt-0.5">Créez une facture et démarrez le service</p>
            </div>
            <button onclick="toggleModal('modal-new')" class="btn btn-icon btn-outline">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('invoices.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Marque véhicule *</label>
                        <input type="text" name="vehicle_brand" required class="form-input" placeholder="BMW, Renault…">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Immatriculation *</label>
                        <input type="text" name="vehicle_plate" required class="form-input" placeholder="AA-000-BB">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nom client</label>
                        <input type="text" name="client_name" class="form-input" placeholder="Nom du client">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone client</label>
                        <input type="tel" name="client_phone" class="form-input" placeholder="+33 6 00 00 00">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Service *</label>
                    <select name="service_id" required class="form-input" onchange="showServiceInfo(this)">
                        <option value="">— Choisir une prestation —</option>
                        @foreach($services as $s)
                        <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-duration="{{ $s->duration }}">
                            {{ $s->name }} — {{ $s->price }}€ ({{ $s->duration }} min)
                        </option>
                        @endforeach
                    </select>
                </div>

                <div id="service-info" class="hidden grid grid-cols-2 gap-3 p-3 bg-[#0d0f1a] rounded-xl border border-[#1e2235]">
                    <div class="text-center">
                        <p class="text-xs text-slate-500 mb-1">Prix</p>
                        <p id="svc-price" class="text-xl font-bold text-emerald-400"></p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-slate-500 mb-1">Durée</p>
                        <p id="svc-duration" class="text-xl font-bold text-indigo-400"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Assigner au laveur *</label>
                    <select name="employee_id" required class="form-input">
                        <option value="">— Choisir un laveur —</option>
                        @foreach($employees as $e)
                        <option value="{{ $e->id }}">{{ $e->code }} — {{ $e->full_name }} ({{ $e->commission_rate }}%)</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('modal-new')" class="btn btn-outline flex-1 justify-center">Annuler</button>
                    <button type="submit" class="btn flex-1 justify-center font-bold"
                            style="background:linear-gradient(135deg,#10b981,#059669);color:#fff">
                        <i class="fas fa-receipt"></i> Créer & Démarrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
function showServiceInfo(select) {
    const opt = select.selectedOptions[0];
    const box = document.getElementById('service-info');
    if (opt.value) {
        document.getElementById('svc-price').textContent = opt.dataset.price + '€';
        document.getElementById('svc-duration').textContent = opt.dataset.duration + ' min';
        box.classList.remove('hidden');
        box.classList.add('grid');
    } else {
        box.classList.add('hidden');
        box.classList.remove('grid');
    }
}
</script>
@endsection
