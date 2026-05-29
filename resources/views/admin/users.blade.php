@extends('layouts.app')
@section('title', 'Utilisateurs')

@section('content')

@php
$carwashes = \App\Models\Carwash::orderBy('name')->get();
$roleInfo = [
    'admin'        => ['label'=>'Admin',          'badge'=>'badge-red',    'icon'=>'fas fa-shield-halved'],
    'manager'      => ['label'=>'Manager',         'badge'=>'badge-blue',   'icon'=>'fas fa-user-tie'],
    'receptionist' => ['label'=>'Réceptionniste',  'badge'=>'badge-yellow', 'icon'=>'fas fa-headset'],
    'employee'     => ['label'=>'Employé',         'badge'=>'badge-green',  'icon'=>'fas fa-id-badge'],
];
@endphp

<div class="page-header">
    <h1 class="page-title">Utilisateurs</h1>
    <p class="page-subtitle">{{ $users->count() }} compte(s) enregistré(s)</p>
</div>

<div class="card overflow-hidden">
    <table class="data-table">
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Rôle</th>
                <th>Centre assigné</th>
                <th>Statut</th>
                <th>Inscrit le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
            @php $ri = $roleInfo[$u->role] ?? ['label'=>$u->role,'badge'=>'badge-gray','icon'=>'fas fa-user']; @endphp
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                            {{ strtoupper(substr($u->first_name,0,1)).strtoupper(substr($u->last_name,0,1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-200">{{ $u->full_name }}</p>
                            <p class="text-xs text-slate-500">{{ $u->email }}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge {{ $ri['badge'] }}">
                        <i class="{{ $ri['icon'] }} mr-1.5 text-[10px]"></i>{{ $ri['label'] }}
                    </span>
                </td>
                <td>
                    @if($u->carwash)
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                        <span class="text-sm text-slate-300">{{ $u->carwash->name }}</span>
                    </div>
                    @else
                    <span class="text-slate-600 text-sm">Non assigné</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $u->is_active ? 'badge-green' : 'badge-red' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-400' : 'bg-rose-400' }} mr-1.5"></span>
                        {{ $u->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </td>
                <td>
                    <span class="text-sm text-slate-500">{{ $u->created_at->format('d/m/Y') }}</span>
                </td>
                <td>
                    @if(!$u->isAdmin())
                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Toggle -->
                        <form method="POST" action="{{ route('admin.users.toggle', $u) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-danger' : 'btn-success' }}">
                                {{ $u->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                        <!-- Assign carwash -->
                        <form method="POST" action="{{ route('admin.users.assign-carwash', $u) }}" class="flex items-center gap-1.5">
                            @csrf @method('PATCH')
                            <select name="carwash_id" class="form-input text-xs py-1.5 px-2 h-auto" style="min-width:130px">
                                @foreach($carwashes as $cw)
                                <option value="{{ $cw->id }}" {{ $u->carwash_id==$cw->id?'selected':'' }}>{{ $cw->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline px-2">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                        </form>
                    </div>
                    @else
                    <span class="text-xs text-slate-600 italic">Super admin</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="py-16 text-center">
                    <i class="fas fa-users text-slate-700 text-4xl mb-3"></i>
                    <p class="text-slate-600 text-sm">Aucun utilisateur</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
