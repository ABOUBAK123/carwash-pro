<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $carwashId = Auth::user()->carwash_id;
        $period = $request->get('period', 'current');

        $employees = Employee::where('carwash_id', $carwashId)->get();

        $salaryData = $employees->map(function ($employee) use ($carwashId, $period) {
            $query = Invoice::where('carwash_id', $carwashId)->where('employee_id', $employee->id);

            if ($period === 'current') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            } elseif ($period === 'last') {
                $query->whereMonth('created_at', now()->subMonth()->month)
                      ->whereYear('created_at', now()->subMonth()->year);
            } elseif ($period === 'quarter') {
                $query->where('created_at', '>=', now()->subMonths(3));
            }

            $invoices = $query->get();
            $totalRevenue = $invoices->sum('total_amount');
            $servicesCount = $invoices->count();

            $salary = match($employee->salary_type) {
                'hourly' => ($employee->hourly_rate ?? 0) * ($employee->hours_worked ?? 0),
                'fixed' => $employee->fixed_salary ?? 0,
                'commission' => $invoices->sum('employee_commission'),
                default => 0,
            };

            return [
                'employee' => $employee,
                'total_salary' => $salary,
                'services_count' => $servicesCount,
                'total_revenue' => $totalRevenue,
            ];
        });

        return view('salary.index', compact('salaryData', 'period'));
    }
}
