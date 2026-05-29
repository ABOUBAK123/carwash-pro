<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfitController extends Controller
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

        [$from, $to] = $this->dateRange($period);

        $revenue = Invoice::where('carwash_id', $carwashId)
            ->where('status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->sum('total_amount');

        $totalExpenses = Expense::where('carwash_id', $carwashId)
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->sum('amount');

        $profit = $revenue - $totalExpenses;
        $margin = $revenue > 0 ? round($profit / $revenue * 100, 1) : 0;

        $expenseBreakdown = Expense::where('carwash_id', $carwashId)
            ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        $revenueBreakdown = Invoice::where('carwash_id', $carwashId)
            ->where('status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('service_name, COUNT(*) as cnt, SUM(total_amount) as total')
            ->groupBy('service_name')
            ->orderByDesc('total')
            ->get();

        $periodLabel = ['week' => 'Cette semaine', 'month' => 'Ce mois', 'quarter' => 'Ce trimestre', 'year' => 'Cette année'][$period] ?? $period;

        return view('profits.index', compact(
            'revenue', 'totalExpenses', 'profit', 'margin',
            'expenseBreakdown', 'revenueBreakdown', 'period', 'periodLabel'
        ));
    }
}
