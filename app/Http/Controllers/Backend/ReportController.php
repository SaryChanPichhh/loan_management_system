<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom  = $request->get('date_from');
        $dateTo    = $request->get('date_to');
        $export    = $request->get('export');   // 'csv' | 'pdf' | null
        $tab       = $request->get('tab', 'collection');

        // ─── 1. Repayment Collection ──────────────────────────────────
        $repaymentQuery = DB::table('repayments as r')
            ->join('loans as l', 'r.loan_id', '=', 'l.id')
            ->join('customers as c', 'l.customer_id', '=', 'c.id')
            ->select(
                'c.name          as customer_name',
                'l.loan_code',
                'r.payment_date',
                'r.amount',
                'r.principal_paid',
                'r.interest_paid',
                'r.late_fee_paid',
                'r.payment_method'
            )
            ->whereNull('l.deleted_at');

        if ($dateFrom) {
            $repaymentQuery->whereDate('r.payment_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $repaymentQuery->whereDate('r.payment_date', '<=', $dateTo);
        }

        $repayments = $repaymentQuery->orderBy('r.payment_date', 'desc')->get();

        $repaymentTotals = [
            'amount'         => $repayments->sum('amount'),
            'principal_paid' => $repayments->sum('principal_paid'),
            'interest_paid'  => $repayments->sum('interest_paid'),
            'late_fee_paid'  => $repayments->sum('late_fee_paid'),
        ];

        // ─── 2. Overdue Loans ─────────────────────────────────────────
        $overdueLoans = DB::table('loans as l')
            ->join('customers as c', 'l.customer_id', '=', 'c.id')
            ->join('loan_accounts as la', 'la.loan_id', '=', 'l.id')
            ->leftJoin('guarantors as g', 'g.customer_id', '=', 'l.customer_id')
            ->select(
                'l.loan_code',
                'c.name             as customer_name',
                'la.outstanding_balance',
                'la.overdue_amount',
                'la.days_past_due',
                DB::raw('MIN(g.full_name) as guarantor_name')
            )
            ->where('la.days_past_due', '>', 0)
            ->whereNull('l.deleted_at')
            ->groupBy(
                'l.id',
                'l.loan_code',
                'c.name',
                'la.outstanding_balance',
                'la.overdue_amount',
                'la.days_past_due'
            )
            ->orderBy('la.days_past_due', 'desc')
            ->get();

        // ─── 3. Portfolio Summary ─────────────────────────────────────
        $statuses  = ['pending', 'active', 'completed', 'defaulted', 'written_off'];
        $rawSummary = DB::table('loans')
            ->whereNull('deleted_at')
            ->whereIn('status', $statuses)
            ->select(
                'status',
                DB::raw('COUNT(*) as loan_count'),
                DB::raw('SUM(principal_amount) as total_principal')
            )
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $portfolio = [];
        foreach ($statuses as $s) {
            $portfolio[$s] = $rawSummary->get($s) ?? (object)[
                'status'          => $s,
                'loan_count'      => 0,
                'total_principal' => 0,
            ];
        }

        // ─── Export: CSV ──────────────────────────────────────────────
        if ($export === 'csv') {
            // Route to the right export based on active tab
            if ($tab === 'overdue') {
                return $this->exportOverdueCsv($overdueLoans);
            }
            // Default: collection
            return $this->exportCollectionCsv($repayments, $repaymentTotals);
        }

        // ─── Export: PDF (print view) ─────────────────────────────────
        if ($export === 'pdf') {
            return view('backend.report.print', compact(
                'repayments',
                'repaymentTotals',
                'overdueLoans',
                'portfolio',
                'dateFrom',
                'dateTo',
                'tab'
            ));
        }

        // ─── Default: HTML view ───────────────────────────────────────
        return view('backend.report.index', compact(
            'repayments',
            'repaymentTotals',
            'overdueLoans',
            'portfolio',
            'dateFrom',
            'dateTo'
        ));
    }

    // ── CSV: Repayment Collection ─────────────────────────────────────
    private function exportCollectionCsv($repayments, array $totals)
    {
        $filename = 'collection_report_' . now()->format('Ymd_His') . '.csv';

        return Response::streamDownload(function () use ($repayments, $totals) {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

            fputcsv($h, [
                'Customer Name',
                'Loan Code',
                'Payment Date',
                'Amount (USD)',
                'Principal Paid',
                'Interest Paid',
                'Late Fee Paid',
                'Payment Method',
            ]);

            foreach ($repayments as $row) {
                fputcsv($h, [
                    $row->customer_name,
                    $row->loan_code,
                    $row->payment_date,
                    number_format($row->amount, 2),
                    number_format($row->principal_paid, 2),
                    number_format($row->interest_paid, 2),
                    number_format($row->late_fee_paid, 2),
                    $row->payment_method ?? '',
                ]);
            }

            // Totals footer row
            fputcsv($h, [
                'TOTAL', '', '',
                number_format($totals['amount'], 2),
                number_format($totals['principal_paid'], 2),
                number_format($totals['interest_paid'], 2),
                number_format($totals['late_fee_paid'], 2),
                '',
            ]);

            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    // ── CSV: Overdue Loans ────────────────────────────────────────────
    private function exportOverdueCsv($overdueLoans)
    {
        $filename = 'overdue_loans_' . now()->format('Ymd_His') . '.csv';

        return Response::streamDownload(function () use ($overdueLoans) {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

            fputcsv($h, [
                'Loan Code',
                'Customer Name',
                'Outstanding Balance (USD)',
                'Overdue Amount (USD)',
                'Days Past Due',
                'Guarantor Name',
            ]);

            foreach ($overdueLoans as $row) {
                fputcsv($h, [
                    $row->loan_code,
                    $row->customer_name,
                    number_format($row->outstanding_balance, 2),
                    number_format($row->overdue_amount, 2),
                    $row->days_past_due,
                    $row->guarantor_name ?? 'None',
                ]);
            }

            fclose($h);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
