@extends('layouts.app')
@section('title', 'Mon espace')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-3xl font-bold text-white mb-6 shadow-lg shadow-indigo-500/30">
        {{ strtoupper(substr($user->first_name,0,1)) }}
    </div>
    <h1 class="text-2xl font-bold text-white mb-2">Bienvenue, {{ $user->full_name }}</h1>
    <p class="text-slate-500">Employé{{ $user->carwash ? ' · ' . $user->carwash->name : '' }}</p>
    <div class="mt-6 px-5 py-3 rounded-xl" style="background:#111322;border:1px solid #1e2235;">
        <p class="text-sm text-slate-400 text-center">Votre espace employé sera disponible prochainement.</p>
    </div>
</div>
@endsection
