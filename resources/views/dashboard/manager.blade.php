@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')

<div class="page-header flex items-center justify-between">
    <div>
        <h1 class="page-title">Tableau de bord</h1>
        <p class="page-subtitle">{{ $user->carwash?->name ?? 'Centre non assigné' }} — {{ now()->isoFormat('dddd D MMMM YYYY') }}</p>
    </div>
    @if($user->carwash_id)
    <a href="{{ route('invoices.index') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvelle facture
    </a>
    @endif
</div>

@if(!$user->carwash_id)
<div class="card p-12 text-center">
    <div class="w-16 h-16 rounded-2xl bg-amber-500/10 flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-triangle-exclamation text-amber-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-semibold text-white mb-2">Aucun centre assigné</h3>
    <p class="text-slate-500 text-sm">Contactez l'administrateur pour être assigné à un centre de lavage.</p>
</div>
@else

<!-- KPI Row -->
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">

    <div class="stat-card col-span-1">
        <div class="w-9 h-9 rounded-xl bg-indigo-500/15 flex items-center justify-center mb-3">
            <i class="fas fa-calendar-day text-indigo-400 text-sm"></i>
        </div>
        <p class="text-2xl font-extrabold text-white">{{ $stats['today_appointments'] }}</p>
        <p class="text-xs text-slate-500 mt-0.5">RDV aujourd'hui</p>
    </div>

    <div class="stat-card col-span-1">
        <div class="w-9 h-9 rounded-xl bg-emerald-500/15 flex items-center justify-center mb-3">
            <i class="fas fa-arrow-trend-up text-emerald-400 text-sm"></i>
        </div>
        <p class="text-2xl font-extrabold text-white">{{ number_format($stats['today_revenue'], 0) }}€</p>
        <p class="text-xs text-slate-500 mt-0.5">Recettes du jour</p>
    </div>

    <div class="stat-card col-span-1">
        <div class="w-9 h-9 rounded-xl bg-violet-500/15 flex items-center justify-center mb-3">
            <i class="fas fa-chart-line text-violet-400 text-sm"></i>
        </div>
        <p class="text-2xl font-extrabold text-white">{{ number_format($stats['month_revenue'], 0) }}€</p>
        <p class="text-xs text-slate-500 mt-0.5">CA du mois</p>
    </div>

    <div class="stat-card col-span-1">
        <div class="w-9 h-9 rounded-xl bg-sky-500/15 flex items-center justify-center mb-3">
            <i class="fas fa-user-group text-sky-400 text-sm"></i>
        </div>
        <p class="text-2xl font-extrabold text-white">{{ $stats['total_clients'] }}</p>
        <p class="text-xs text-slate-500 mt-0.5">Clients total</p>
    </div>

    <div class="stat-card col-span-1">
        <div class="w-9 h-9 rounded-xl bg-teal-500/15 flex items-center justify-center mb-3">
            <i class="fas fa-id-badge text-teal-400 text-sm"></i>
        </div>
        <p class="text-2xl font-extrabold text-white">{{ $stats['active_employees'] }}</p>
        <p class="text-xs text-slate-500 mt-0.5">Employés actifs</p>
    </div>

    <div class="stat-card col-span-1">
        <div class="w-9 h-9 rounded-xl bg-amber-500/15 flex items-center justify-center mb-3">
            <i class="fas fa-clock text-amber-400 text-sm"></i>
        </div>
        <p class="text-2xl font-extrabold text-white">{{ $stats['pending_appointments'] }}</p>
        <p class="text-xs text-slate-500 mt-0.5">RDV en attente</p>
    </div>

</div>

<!-- Two columns -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    <!-- RDV du jour -->
    <div class="card">
        <div class="flex items-center justify-between px-5 py-4 border-b border-[#1e2235]">
            <div class="flex items-center gap-2">
                <i class="fas fa-calendar-days text-indigo-400 text-sm"></i>
                <p class="text-sm font-semibold text-white">Rendez-vous du jour</p>
                <span class="badge badge-blue">{{ $today_appointments->count() }}</span>
            </div>
            <a href="{{ route('appointments.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300">Voir tout →</a>
        </div>
        <div class="divide-y divide-[#161829]">
            @forelse($today_appointments as $apt)
            <div class="flex items-center justify-between px-5 py-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-car text-slate-400 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-200">{{ $apt->client_name }}</p>
                        <p class="text-xs text-slate-500">{{ $apt->vehicle_brand }} · {{ $apt->vehicle_plate }} · {{ substr($apt->appointment_time, 0, 5) }}</p>
                    </div>
                </div>
                @php
                $colors = ['scheduled'=>'badge-blue','in_progress'=>'badge-yellow','completed'=>'badge-green','cancelled'=>'badge-red'];
                @endphp
                <span class="badge {{ $colors[$apt->status] ?? 'badge-gray' }}">{{ $apt->status_label }}</span>
            </div>
            @empty
            <div class="px-5 py-10 text-center">
                <i class="fas fa-calendar-xmark text-slate-700 text-3xl mb-3"></i>
                <p class="text-sm text-slate-600">Aucun rendez-vous aujourd'hui</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Dernières factures -->
    <div class="card">
        <div class="flex items-center justify-between px-5 py-4 border-b border-[#1e2235]">
            <div class="flex items-center gap-2">
                <i class="fas fa-receipt text-emerald-400 text-sm"></i>
                <p class="text-sm font-semibold text-white">Dernières factures</p>
            </div>
            <a href="{{ route('invoices.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300">Voir tout →</a>
        </div>
        <div class="divide-y divide-[#161829]">
            @forelse($recent_invoices as $inv)
            <div class="flex items-center justify-between px-5 py-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-file-invoice text-emerald-400 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-200 font-mono">{{ $inv->invoice_number }}</p>
                        <p class="text-xs text-slate-500">{{ $inv->vehicle_plate }} · {{ $inv->service_name }}</p>
                    </div>
                </div>
                <span class="text-emerald-400 font-bold text-sm">{{ number_format($inv->total_amount, 2) }}€</span>
            </div>
            @empty
            <div class="px-5 py-10 text-center">
                <i class="fas fa-file-circle-xmark text-slate-700 text-3xl mb-3"></i>
                <p class="text-sm text-slate-600">Aucune facture récente</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endif

@endsection
