<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    private function dateRange(string $period): array
    {
        return match($period) {
            'week'    => [now()->startOfWeek(), now()->endOfWeek()],
            'quarter' => [now()->startOfQuarter(), now()->endOfQuarter()],
            'year'    => [now()->startOfYear(), now()->endOfYear()],
            default   => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    public function index(Request $request)
    {
        $carwashId = Auth::user()->carwash_id;
        $period    = $request->get('period', 'month');
        $typeFilter = $request->get('type', 'all');

        [$from, $to] = $this->dateRange($period);

        $query = Expense::where('carwash_id', $carwashId)
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()]);

        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        $expenses = $query->orderByDesc('expense_date')->get();

        $byType = Expense::where('carwash_id', $carwashId)
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('type, SUM(amount) as total, COUNT(*) as cnt')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $total = $expenses->sum('amount');

        return view('expenses.index', compact('expenses', 'byType', 'total', 'period', 'typeFilter'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'         => 'required|in:electricity,water,products,maintenance,salary,other',
            'amount'       => 'required|numeric|min:0.01',
            'description'  => 'nullable|string|max:255',
            'expense_date' => 'required|date',
        ]);

        Expense::create([
            ...$data,
            'carwash_id' => Auth::user()->carwash_id,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Dépense enregistrée.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Dépense supprimée.');
    }
}
