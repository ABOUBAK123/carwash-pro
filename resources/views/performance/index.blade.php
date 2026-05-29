@extends('layouts.app')
@section('title', 'Performance')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Performance</h1>
        <p class="page-subtitle">Analyse du mois de {{ now()->locale('fr')->isoFormat('MMMM YYYY') }}</p>
    </div>
</div>

<!-- Revenue KPIs -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-sky-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-calendar-day text-sky-400"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Aujourd'hui</p>
            <p class="text-2xl font-extrabold text-white">{{ number_format($todayRevenue, 2) }}€</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-violet-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-chart-line text-violet-400"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Cette semaine</p>
            <p class="text-2xl font-extrabold text-white">{{ number_format($weekRevenue, 2) }}€</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-coins text-emerald-400"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Ce mois</p>
            <p class="text-2xl font-extrabold text-emerald-400">{{ number_format($monthRevenue, 2) }}€</p>
        </div>
    </div>
</div>

<!-- Services & Clients Stats -->
<div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-6 gap-3 mb-6">
    <div class="stat-card text-center p-4">
        <p class="text-2xl font-extrabold text-emerald-400">{{ $servicesCompleted }}</p>
        <p class="text-xs text-slate-500 mt-1">Terminés (total)</p>
    </div>
    <div class="stat-card text-center p-4">
        <p class="text-2xl font-extrabold text-amber-400">{{ $servicesPending }}</p>
        <p class="text-xs text-slate-500 mt-1">En attente</p>
    </div>
    <div class="stat-card text-center p-4">
        <p class="text-2xl font-extrabold text-rose-400">{{ $servicesCancelled }}</p>
        <p class="text-xs text-slate-500 mt-1">Annulés</p>
    </div>
    <div class="stat-card text-center p-4">
        <p class="text-2xl font-extrabold text-indigo-400">{{ $monthCompleted }}</p>
        <p class="text-xs text-slate-500 mt-1">Ce mois</p>
    </div>
    <div class="stat-card text-center p-4">
        <p class="text-2xl font-extrabold text-white">{{ $totalClients }}</p>
        <p class="text-xs text-slate-500 mt-1">Clients total</p>
    </div>
    <div class="stat-card text-center p-4">
        <p class="text-2xl font-extrabold text-sky-400">{{ $newClientsMonth }}</p>
        <p class="text-xs text-slate-500 mt-1">Nouveaux/mois</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

    <!-- Performance employés -->
    <div class="card overflow-hidden">
        <div class="flex items-center gap-2 px-5 py-4 border-b border-[#1e2235]">
            <i class="fas fa-id-badge text-indigo-400 text-sm"></i>
            <p class="text-sm font-semibold text-white">Performance employés — ce mois</p>
        </div>
        @if($employeePerformance->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Employé</th>
                    <th class="text-center">Services</th>
                    <th class="text-right">CA généré</th>
                    <th class="text-right">Part %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employeePerformance as $i => $perf)
                @php $pct = $monthTotalRevenue > 0 ? round($perf['revenue'] / $monthTotalRevenue * 100) : 0; @endphp
                <tr>
                    <td>
                        <span class="text-sm font-bold {{ $i===0?'text-amber-400':($i===1?'text-slate-400':($i===2?'text-amber-700':'text-slate-600')) }}">
                            #{{ $i + 1 }}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-teal-500/20 flex items-center justify-center text-xs font-bold text-teal-400 flex-shrink-0">
                                {{ strtoupper(substr($perf['employee']->first_name, 0, 1)) }}
                            </div>
                            <span class="text-sm text-slate-200">{{ $perf['employee']->full_name }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="text-sm font-bold text-slate-200">{{ $perf['services_count'] }}</span>
                    </td>
                    <td class="text-right">
                        <span class="text-sm font-bold text-emerald-400">{{ number_format($perf['revenue'], 2) }}€</span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <div class="w-16 h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 rounded-full" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 w-8">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="py-10 text-center">
            <i class="fas fa-id-badge text-slate-700 text-3xl mb-2"></i>
            <p class="text-slate-600 text-sm">Aucune donnée ce mois</p>
        </div>
        @endif
    </div>

    <!-- Top services -->
    <div class="card overflow-hidden">
        <div class="flex items-center gap-2 px-5 py-4 border-b border-[#1e2235]">
            <i class="fas fa-star text-amber-400 text-sm"></i>
            <p class="text-sm font-semibold text-white">Top services — ce mois</p>
        </div>
        @if($topServices->count())
        @php $svcMax = $topServices->max('cnt') ?: 1; @endphp
        <div class="divide-y divide-[#161829]">
            @foreach($topServices as $i => $svc)
            <div class="flex items-center gap-4 px-5 py-3.5">
                <span class="text-lg font-extrabold {{ $i===0?'text-amber-400':($i===1?'text-slate-400':($i===2?'text-amber-700':'text-slate-600')) }} w-6 text-center flex-shrink-0">
                    {{ $i + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-200 truncate">{{ $svc->service_name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="flex-1 h-1.5 bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full" style="width:{{ round($svc->cnt/$svcMax*100) }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-amber-400">{{ $svc->cnt }}×</p>
                    <p class="text-xs text-emerald-400">{{ number_format($svc->revenue, 2) }}€</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-10 text-center">
            <i class="fas fa-star text-slate-700 text-3xl mb-2"></i>
            <p class="text-slate-600 text-sm">Aucune facture ce mois</p>
        </div>
        @endif
    </div>

</div>

@endsection
