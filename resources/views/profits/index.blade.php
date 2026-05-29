@extends('layouts.app')
@section('title', 'Analyse des profits')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Analyse des profits</h1>
        <p class="page-subtitle">{{ $periodLabel }}</p>
    </div>
    <form method="GET">
        <select name="period" onchange="this.form.submit()" class="form-input text-sm py-2" style="width:auto">
            @foreach(['week'=>'Cette semaine','month'=>'Ce mois','quarter'=>'Ce trimestre','year'=>'Cette année'] as $k=>$l)
            <option value="{{ $k }}" {{ $period===$k?'selected':'' }}>{{ $l }}</option>
            @endforeach
        </select>
    </form>
</div>

<!-- KPIs -->
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-7">
    <div class="stat-card">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-500/15 flex items-center justify-center">
                <i class="fas fa-chart-bar text-indigo-400 text-sm"></i>
            </div>
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Recettes</span>
        </div>
        <p class="text-3xl font-extrabold text-white">{{ number_format($revenue, 2) }}€</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl bg-rose-500/15 flex items-center justify-center">
                <i class="fas fa-arrow-trend-down text-rose-400 text-sm"></i>
            </div>
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Dépenses</span>
        </div>
        <p class="text-3xl font-extrabold text-rose-400">{{ number_format($totalExpenses, 2) }}€</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl {{ $profit >= 0 ? 'bg-emerald-500/15' : 'bg-rose-500/15' }} flex items-center justify-center">
                <i class="fas {{ $profit >= 0 ? 'fa-arrow-trend-up text-emerald-400' : 'fa-arrow-trend-down text-rose-400' }} text-sm"></i>
            </div>
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Profit net</span>
        </div>
        <p class="text-3xl font-extrabold {{ $profit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
            {{ $profit >= 0 ? '+' : '' }}{{ number_format($profit, 2) }}€
        </p>
    </div>
    <div class="stat-card">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl {{ $margin >= 0 ? 'bg-sky-500/15' : 'bg-rose-500/15' }} flex items-center justify-center">
                <i class="fas fa-percent text-sky-400 text-sm"></i>
            </div>
            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Marge</span>
        </div>
        <p class="text-3xl font-extrabold {{ $margin >= 0 ? 'text-sky-400' : 'text-rose-400' }}">{{ $margin }}%</p>
    </div>
</div>

<!-- Barre visuelle Recettes vs Dépenses -->
@if($revenue > 0 || $totalExpenses > 0)
@php $max = max($revenue, $totalExpenses, 1); @endphp
<div class="card p-6 mb-6">
    <p class="text-sm font-semibold text-slate-400 mb-4">Recettes vs Dépenses</p>
    <div class="space-y-3">
        <div>
            <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                <span>Recettes</span>
                <span class="text-indigo-400 font-bold">{{ number_format($revenue, 2) }}€</span>
            </div>
            <div class="h-3 bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 rounded-full" style="width:{{ round($revenue/$max*100) }}%"></div>
            </div>
        </div>
        <div>
            <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                <span>Dépenses</span>
                <span class="text-rose-400 font-bold">{{ number_format($totalExpenses, 2) }}€</span>
            </div>
            <div class="h-3 bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full bg-rose-500 rounded-full" style="width:{{ round($totalExpenses/$max*100) }}%"></div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

    <!-- Dépenses par catégorie -->
    <div class="card p-5">
        <div class="flex items-center gap-2 mb-5">
            <i class="fas fa-chart-pie text-rose-400 text-sm"></i>
            <p class="text-sm font-semibold text-white">Dépenses par catégorie</p>
        </div>
        @if($expenseBreakdown->count())
        @php $expMax = $expenseBreakdown->max('total') ?: 1; $typeLabels = \App\Models\Expense::$typeLabels; $typeBadges = \App\Models\Expense::$typeBadges; @endphp
        <div class="space-y-3">
            @foreach($expenseBreakdown as $row)
            <div>
                <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                    <span class="badge {{ $typeBadges[$row->type] ?? 'badge-gray' }}">{{ $typeLabels[$row->type] ?? $row->type }}</span>
                    <span class="font-bold text-slate-300">{{ number_format($row->total, 2) }}€
                        <span class="text-slate-600">({{ round($row->total/$totalExpenses*100) }}%)</span>
                    </span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-rose-500 rounded-full opacity-70" style="width:{{ round($row->total/$expMax*100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-8 text-center">
            <i class="fas fa-receipt text-slate-700 text-3xl mb-2"></i>
            <p class="text-slate-600 text-sm">Aucune dépense sur cette période</p>
        </div>
        @endif
    </div>

    <!-- Recettes par service -->
    <div class="card p-5">
        <div class="flex items-center gap-2 mb-5">
            <i class="fas fa-chart-bar text-indigo-400 text-sm"></i>
            <p class="text-sm font-semibold text-white">Recettes par service</p>
        </div>
        @if($revenueBreakdown->count())
        @php $revMax = $revenueBreakdown->max('total') ?: 1; @endphp
        <div class="space-y-3">
            @foreach($revenueBreakdown as $row)
            <div>
                <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                    <span class="text-slate-300 font-medium">{{ $row->service_name }}</span>
                    <span class="font-bold text-indigo-300">{{ number_format($row->total, 2) }}€
                        <span class="text-slate-600">({{ $row->cnt }} fois)</span>
                    </span>
                </div>
                <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full opacity-70" style="width:{{ round($row->total/$revMax*100) }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="py-8 text-center">
            <i class="fas fa-file-invoice text-slate-700 text-3xl mb-2"></i>
            <p class="text-slate-600 text-sm">Aucune facture sur cette période</p>
        </div>
        @endif
    </div>

</div>

@endsection
