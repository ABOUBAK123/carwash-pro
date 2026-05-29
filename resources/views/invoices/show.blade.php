@extends('layouts.app')
@section('title', 'Facture ' . $invoice->invoice_number)

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('invoices.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <button onclick="window.print()" class="btn btn-outline">
            <i class="fas fa-print"></i> Imprimer
        </button>
    </div>

    <!-- Invoice card -->
    <div class="card p-8">

        <!-- Header -->
        <div class="flex items-start justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-droplet text-white"></i>
                </div>
                <div>
                    <p class="text-xl font-extrabold text-white">CarWash Pro</p>
                    @if($invoice->carwash)
                    <p class="text-sm text-slate-500">{{ $invoice->carwash->name }}</p>
                    <p class="text-xs text-slate-600">{{ $invoice->carwash->address }}, {{ $invoice->carwash->city }}</p>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-2xl font-extrabold text-indigo-400 font-mono">{{ $invoice->invoice_number }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $invoice->created_at->format('d/m/Y à H:i') }}</p>
                <span class="badge badge-green mt-2 inline-flex">
                    <i class="fas fa-circle-check mr-1.5 text-[10px]"></i> Payée
                </span>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-[#1e2235] mb-6"></div>

        <!-- Client + Véhicule -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-[#0d0f1a] border border-[#1e2235] rounded-xl p-4">
                <p class="text-xs text-slate-500 mb-2 font-semibold uppercase tracking-wider">
                    <i class="fas fa-user mr-1.5"></i>Client
                </p>
                <p class="text-white font-bold">{{ $invoice->client_name ?? 'Client' }}</p>
                <p class="text-slate-400 text-sm mt-1">{{ $invoice->client_phone ?? '—' }}</p>
            </div>
            <div class="bg-[#0d0f1a] border border-[#1e2235] rounded-xl p-4">
                <p class="text-xs text-slate-500 mb-2 font-semibold uppercase tracking-wider">
                    <i class="fas fa-car mr-1.5"></i>Véhicule
                </p>
                <p class="text-white font-bold">{{ $invoice->vehicle_brand }}</p>
                <p class="font-mono text-indigo-400 text-sm mt-1 tracking-widest">{{ $invoice->vehicle_plate }}</p>
            </div>
        </div>

        <!-- Service row -->
        <div class="bg-[#0d0f1a] border border-[#1e2235] rounded-xl mb-6 overflow-hidden">
            <div class="grid grid-cols-3 px-5 py-3 border-b border-[#1e2235]">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Service</p>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Laveur</p>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider text-right">Prix</p>
            </div>
            <div class="grid grid-cols-3 px-5 py-4 items-center">
                <div>
                    <p class="font-semibold text-slate-200">{{ $invoice->service_name }}</p>
                </div>
                <div>
                    @if($invoice->employee)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center text-xs font-bold text-teal-400">
                            {{ strtoupper(substr($invoice->employee->first_name,0,1)) }}
                        </div>
                        <span class="text-sm text-slate-300">{{ $invoice->employee->full_name }}</span>
                    </div>
                    @else <span class="text-slate-600 text-sm">—</span> @endif
                </div>
                <p class="text-right text-2xl font-extrabold text-emerald-400">{{ number_format($invoice->total_amount,2) }}€</p>
            </div>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-amber-500/5 border border-amber-500/15 rounded-xl p-4">
                <p class="text-xs text-amber-400/70 font-semibold uppercase tracking-wider mb-1">
                    Commission laveur ({{ $invoice->employee?->commission_rate ?? 0 }}%)
                </p>
                <p class="text-2xl font-extrabold text-amber-400">{{ number_format($invoice->employee_commission, 2) }}€</p>
            </div>
            <div class="bg-emerald-500/5 border border-emerald-500/15 rounded-xl p-4 text-right">
                <p class="text-xs text-emerald-400/70 font-semibold uppercase tracking-wider mb-1">Total facturé</p>
                <p class="text-3xl font-extrabold text-emerald-400">{{ number_format($invoice->total_amount, 2) }}€</p>
            </div>
        </div>

    </div>
</div>

@endsection
