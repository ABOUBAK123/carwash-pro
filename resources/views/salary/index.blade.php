@extends('layouts.app')
@section('title', 'Salaires')

@section('content')

@php
$totalSalaries = collect($salaryData)->sum('total_salary');
$totalRevenue  = collect($salaryData)->sum('total_revenue');
$totalServices = collect($salaryData)->sum('services_count');
$periodLabel   = ['current'=>'Mois actuel','last'=>'Mois dernier','quarter'=>'Trimestre'][$period] ?? $period;
@endphp

<div class="page-header flex items-start justify-between">
    <div>
        <h1 class="page-title">Gestion des salaires</h1>
        <p class="page-subtitle">Période : <span class="text-indigo-400">{{ $periodLabel }}</span></p>
    </div>
    <form method="GET" class="flex items-center gap-2">
        <select name="period" onchange="this.form.submit()" class="form-input text-sm py-2 px-3" style="width:auto">
            <option value="current" {{ $period==='current'?'selected':'' }}>Mois actuel</option>
            <option value="last"    {{ $period==='last'?'selected':'' }}>Mois dernier</option>
            <option value="quarter" {{ $period==='quarter'?'selected':'' }}>Trimestre</option>
        </select>
    </form>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-7">
    <div class="stat-card flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-wallet text-emerald-400"></i>
        </div>
        <div>
            <p class="text-xl font-extrabold text-white">{{ number_format($totalSalaries,2) }}€</p>
            <p class="text-xs text-slate-500">Total salaires</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-indigo-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-chart-bar text-indigo-400"></i>
        </div>
        <div>
            <p class="text-xl font-extrabold text-white">{{ number_format($totalRevenue,2) }}€</p>
            <p class="text-xs text-slate-500">Chiffre d'affaires</p>
        </div>
    </div>
    <div class="stat-card flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-amber-500/15 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-car text-amber-400"></i>
        </div>
        <div>
            <p class="text-xl font-extrabold text-white">{{ $totalServices }}</p>
            <p class="text-xs text-slate-500">Services réalisés</p>
        </div>
    </div>
</div>

<div class="card overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Employé</th>
                <th>Type rémunération</th>
                <th>Services</th>
                <th>CA généré</th>
                <th>Salaire à payer</th>
                <th>Avancement</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salaryData as $data)
            @php
            $emp = $data['employee'];
            $pct = $totalRevenue > 0 ? ($data['total_revenue'] / $totalRevenue * 100) : 0;
            $typeInfo = match($emp->salary_type) {
                'hourly'     => ['label'=>'Horaire — '.$emp->hourly_rate.'€/h', 'badge'=>'badge-blue'],
                'fixed'      => ['label'=>'Fixe — '.number_format($emp->fixed_salary,0).'€/mois', 'badge'=>'badge-purple'],
                'commission' => ['label'=>'Commission '.$emp->commission_rate.'%', 'badge'=>'badge-yellow'],
                default      => ['label'=>$emp->salary_type, 'badge'=>'badge-gray'],
            };
            @endphp
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
                    <span class="badge {{ $typeInfo['badge'] }}">{{ $typeInfo['label'] }}</span>
                </td>
                <td>
                    <span class="text-xl font-extrabold text-slate-200">{{ $data['services_count'] }}</span>
                </td>
                <td>
                    <span class="font-semibold text-indigo-300">{{ number_format($data['total_revenue'],2) }}€</span>
                </td>
                <td>
                    <span class="text-2xl font-extrabold text-emerald-400">{{ number_format($data['total_salary'],2) }}€</span>
                </td>
                <td class="w-36">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-1.5 bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full transition-all" style="width:{{ round($pct) }}%"></div>
                        </div>
                        <span class="text-xs text-slate-500 w-8 text-right">{{ round($pct) }}%</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <i class="fas fa-wallet text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucun employé</p>
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="border-t border-[#1e2235]">
            <tr class="bg-[#0d0f1a]">
                <td colspan="4" class="px-4 py-4 text-right font-bold text-slate-400 text-sm">Total à verser :</td>
                <td class="px-4 py-4">
                    <span class="text-2xl font-extrabold text-emerald-400">{{ number_format($totalSalaries,2) }}€</span>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

@endsection
