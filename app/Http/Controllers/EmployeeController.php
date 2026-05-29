<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $carwashId = Auth::user()->carwash_id;
        $employees = Employee::where('carwash_id', $carwashId)->orderBy('created_at', 'desc')->get();
        return view('employees.index', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'salary_type' => 'required|in:hourly,fixed,commission',
            'hourly_rate' => 'nullable|numeric|min:0',
            'fixed_salary' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $carwashId = Auth::user()->carwash_id;
        $data['carwash_id'] = $carwashId;
        $data['code'] = Employee::generateCode($carwashId);

        Employee::create($data);
        return back()->with('success', 'Employé créé avec succès.');
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'salary_type' => 'required|in:hourly,fixed,commission',
            'hourly_rate' => 'nullable|numeric|min:0',
            'fixed_salary' => 'nullable|numeric|min:0',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);

        $employee->update($data);
        return back()->with('success', 'Employé mis à jour.');
    }

    public function toggle(Employee $employee)
    {
        $employee->update(['is_active' => !$employee->is_active]);
        $msg = $employee->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Employé {$msg}.");
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return back()->with('success', 'Employé supprimé.');
    }
}
