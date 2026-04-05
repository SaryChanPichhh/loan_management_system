<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\Repayment;
use App\Models\Customer;
use App\Models\LoanSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 📊 CARD STATS
        $activeLoansCount = Loan::where('status', 'active')->count();
        $approvedLoansCount = LoanApplication::where('status', 'approved')->count();
        $pendingLoansCount = LoanApplication::where('status', 'pending')->count();
        $totalDisbursed = Loan::sum('principal_amount');
        $overdueCount = LoanSchedule::where('status', 'overdue')->count();
        $totalCustomers = Customer::count();
        $totalCollected = Repayment::sum('amount');
        
        $today = Carbon::today();
        $customersDueTodayCount = LoanSchedule::whereDate('due_date', $today)
            ->where('loan_schedules.status', '!=', 'paid')
            ->join('loans', 'loan_schedules.loan_id', '=', 'loans.id')
            ->distinct('loans.customer_id')
            ->count('loans.customer_id');
            
        $amountPaidToday = Repayment::whereDate('created_at', $today)->sum('amount');

        $todayRepayments = Repayment::with(['loan.customer', 'schedule'])
            ->whereDate('created_at', $today)
            ->latest()
            ->get();

        // 📈 DISBURSEMENT VS COLLECTION (Last 6 Months)
        $months = [];
        $disbursements = [];
        $collections = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');

            // Disbursed in this month
            $disbursements[] = Loan::whereYear('start_date', $month->year)
                ->whereMonth('start_date', $month->month)
                ->sum('principal_amount');

            // Collected in this month
            $collections[] = Repayment::whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount');
        }

        // 🎯 PAR DISTRIBUTION (Portfolio at Risk)
        // Groups: 1-30, 31-60, 61-90, 91+ days overdue
        $today = Carbon::today();
        
        $parData = [
            '1-30 Days' => LoanSchedule::where('status', 'overdue')
                ->whereBetween('due_date', [$today->copy()->subDays(30), $today->copy()->subDays(1)])
                ->count(),
            '31-60 Days' => LoanSchedule::where('status', 'overdue')
                ->whereBetween('due_date', [$today->copy()->subDays(60), $today->copy()->subDays(31)])
                ->count(),
            '61-90 Days' => LoanSchedule::where('status', 'overdue')
                ->whereBetween('due_date', [$today->copy()->subDays(90), $today->copy()->subDays(61)])
                ->count(),
            '91+ Days' => LoanSchedule::where('status', 'overdue')
                ->where('due_date', '<', $today->copy()->subDays(90))
                ->count(),
        ];

        return view('backend.dashboard.index', compact(
            'activeLoansCount', 
            'approvedLoansCount',
            'pendingLoansCount',
            'totalDisbursed', 
            'overdueCount', 
            'totalCustomers',
            'totalCollected',
            'customersDueTodayCount',
            'amountPaidToday',
            'todayRepayments',
            'months',
            'disbursements',
            'collections',
            'parData'
        ));
    }
}
