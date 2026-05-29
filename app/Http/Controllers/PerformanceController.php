<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceController extends Controller
{
    public function index()
    {
        $carwashId = Auth::user()->carwash_id;

        // Revenue
        $todayRevenue = Invoice::where('carwash_id', $carwashId)
            ->where('status', 'paid')->whereDate('created_at', today())->sum('total_amount');
        $weekRevenue = Invoice::where('carwash_id', $carwashId)
            ->where('status', 'paid')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_amount');
        $monthRevenue = Invoice::where('carwash_id', $carwashId)
            ->where('status', 'paid')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total_amount');

        // Services stats (all time)
        $servicesCompleted = Appointment::where('carwash_id', $carwashId)->where('status', 'completed')->count();
        $servicesPending   = Appointment::where('carwash_id', $carwashId)->where('status', 'scheduled')->count();
        $servicesCancelled = Appointment::where('carwash_id', $carwashId)->where('status', 'cancelled')->count();

        // Services this month
        $monthCompleted = Appointment::where('carwash_id', $carwashId)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        // Clients
        $totalClients    = Client::where('carwash_id', $carwashId)->count();
        $newClientsMonth = Client::where('carwash_id', $carwashId)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        // Employee performance this month
        $employees = Employee::where('carwash_id', $carwashId)->where('is_active', true)->get();
        $employeePerformance = $employees->map(function ($emp) use ($carwashId) {
            $invoices = Invoice::where('carwash_id', $carwashId)
                ->where('employee_id', $emp->id)
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->get();
            return [
                'employee'       => $emp,
                'services_count' => $invoices->count(),
                'revenue'        => $invoices->sum('total_amount'),
                'commission'     => $invoices->sum('employee_commission'),
            ];
        })->sortByDesc('revenue')->values();

        $monthTotalRevenue = $employeePerformance->sum('revenue');

        // Top services this month
        $topServices = Invoice::where('carwash_id', $carwashId)
            ->where('status', 'paid')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('service_name, COUNT(*) as cnt, SUM(total_amount) as revenue')
            ->groupBy('service_name')
            ->orderByDesc('cnt')
            ->take(6)
            ->get();

        return view('performance.index', compact(
            'todayRevenue', 'weekRevenue', 'monthRevenue',
            'servicesCompleted', 'servicesPending', 'servicesCancelled', 'monthCompleted',
            'totalClients', 'newClientsMonth',
            'employeePerformance', 'monthTotalRevenue',
            'topServices'
        ));
    }
}
