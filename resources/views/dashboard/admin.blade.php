@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')

<div class="page-header">
    <h1 class="page-title">Vue d'ensemble</h1>
    <p class="page-subtitle">Tableau de bord administrateur — tous les centres</p>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <div class="stat-card">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/15 flex items-center justify-center">
                <i class="fas fa-building text-indigo-400"></i>
            </div>
            <span class="badge badge-blue">Total</span>
        </div>
        <p class="text-3xl font-extrabold text-white mb-1">{{ $stats['total_carwashes'] }}</p>
        <p class="text-xs text-slate-500 font-medium">Centres de lavage</p>
    </div>

    <div class="stat-card">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/15 flex items-center justify-center">
                <i class="fas fa-circle-check text-emerald-400"></i>
            </div>
            <span class="badge badge-green">Actifs</span>
        </div>
        <p class="text-3xl font-extrabold text-white mb-1">{{ $stats['active_carwashes'] }}</p>
        <p class="text-xs text-slate-500 font-medium">Centres opérationnels</p>
    </div>

    <div class="stat-card">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-violet-500/15 flex items-center justify-center">
                <i class="fas fa-users text-violet-400"></i>
            </div>
            <span class="badge badge-purple">Comptes</span>
        </div>
        <p class="text-3xl font-extrabold text-white mb-1">{{ $stats['total_users'] }}</p>
        <p class="text-xs text-slate-500 font-medium">Utilisateurs inscrits</p>
    </div>

    <div class="stat-card">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-amber-500/15 flex items-center justify-center">
                <i class="fas fa-coins text-amber-400"></i>
            </div>
            <span class="badge badge-yellow">CA</span>
        </div>
        <p class="text-3xl font-extrabold text-white mb-1">{{ number_format($stats['total_revenue'], 0) }}€</p>
        <p class="text-xs text-slate-500 font-medium">Chiffre d'affaires total</p>
    </div>

</div>

<!-- Quick actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    <div class="card p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-xl bg-indigo-500/15 flex items-center justify-center">
                <i class="fas fa-building text-indigo-400 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Centres de lavage</p>
                <p class="text-xs text-slate-500">Activer, désactiver, gérer</p>
            </div>
        </div>
        <a href="{{ route('admin.carwashes') }}"
           class="btn btn-primary w-full justify-center">
            Gérer les centres <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <div class="card p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-xl bg-violet-500/15 flex items-center justify-center">
                <i class="fas fa-users text-violet-400 text-sm"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Utilisateurs</p>
                <p class="text-xs text-slate-500">Activer les comptes, assigner les centres</p>
            </div>
        </div>
        <a href="{{ route('admin.users') }}"
           class="btn btn-outline w-full justify-center">
            Gérer les utilisateurs <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

</div>

@endsection
