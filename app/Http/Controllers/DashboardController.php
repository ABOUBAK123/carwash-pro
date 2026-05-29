<?php

namespace App\Http\Controllers;

use App\Models\Carwash;
use App\Models\Invoice;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $stats = [
                'total_carwashes' => Carwash::count(),
                'active_carwashes' => Carwash::where('is_active', true)->count(),
                'total_users' => \App\Models\User::count(),
                'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            ];
            return view('dashboard.admin', compact('stats'));
        }

        if ($user->isCommissionnaire()) {
            return redirect()->route('commissionnaire.dashboard');
        }

        if ($user->isManager() || $user->isReceptionist()) {
            $carwashId = $user->carwash_id;
            $today = now()->toDateString();

            $stats = [
                'today_appointments' => Appointment::where('carwash_id', $carwashId)
                    ->whereDate('appointment_date', $today)->count(),
                'today_revenue' => Invoice::where('carwash_id', $carwashId)
                    ->whereDate('created_at', $today)->where('status', 'paid')->sum('total_amount'),
                'total_clients' => Client::where('carwash_id', $carwashId)->count(),
                'active_employees' => Employee::where('carwash_id', $carwashId)->where('is_active', true)->count(),
                'month_revenue' => Invoice::where('carwash_id', $carwashId)
                    ->whereMonth('created_at', now()->month)->where('status', 'paid')->sum('total_amount'),
                'pending_appointments' => Appointment::where('carwash_id', $carwashId)
                    ->whereDate('appointment_date', $today)->where('status', 'scheduled')->count(),
            ];

            $recent_invoices = Invoice::where('carwash_id', $carwashId)
                ->orderBy('created_at', 'desc')->limit(5)->get();

            $today_appointments = Appointment::where('carwash_id', $carwashId)
                ->whereDate('appointment_date', $today)
                ->with('employee', 'service')
                ->orderBy('appointment_time')->get();

            return view('dashboard.manager', compact('stats', 'recent_invoices', 'today_appointments', 'user'));
        }

        return view('dashboard.employee', ['user' => $user]);
    }
}
