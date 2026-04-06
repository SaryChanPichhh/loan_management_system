<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanSchedule;
use App\Models\Repayment;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Count of loans where status = active
        $totalActiveLoans = Loan::where('status', 'active')->count();

        // 2. Sum of outstanding_balance from loan_accounts joined with active loans
        $totalOutstandingBalance = Loan::where('loans.status', 'active')
            ->join('loan_accounts', 'loans.id', '=', 'loan_accounts.loan_id')
            ->sum('loan_accounts.outstanding_balance');

        // 3. Sum of repayments this calendar month where status = completed
        $totalCollectedThisMonth = Repayment::where('status', 'completed')
            ->whereMonth('payment_date', Carbon::now()->month)
            ->whereYear('payment_date', Carbon::now()->year)
            ->sum('amount');

        // 4. Count of active loans where days_past_due > 0
        $overdueCount = Loan::where('loans.status', 'active')
            ->join('loan_accounts', 'loans.id', '=', 'loan_accounts.loan_id')
            ->where('loan_accounts.days_past_due', '>', 0)
            ->count();

        // 5. Last 5 repayments with customer name, amount, payment date
        $recentRepayments = Repayment::with(['loan.customer'])
            ->orderByDesc('payment_date')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // 6. Loan schedules due within the next 7 days where status = pending
        $upcomingDue = LoanSchedule::with(['loan.customer'])
            ->where('status', 'pending')
            ->whereBetween('due_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('due_date')
            ->get();

        return view('backend.dashboard.index', compact(
            'totalActiveLoans',
            'totalOutstandingBalance',
            'totalCollectedThisMonth',
            'overdueCount',
            'recentRepayments',
            'upcomingDue',
        ));
    }
}

