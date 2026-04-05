<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repayment;
use App\Models\Loan;
use App\Models\LoanSchedule;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RepaymentController extends Controller
{
    /**
     * Display a listing of the repayments.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        
        $query = Repayment::with(['loan.customer', 'schedule', 'receivedBy'])
            ->latest();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($search) {
            $query->whereHas('loan.customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })->orWhereHas('loan', function($q) use ($search) {
                $q->where('loan_code', 'like', "%{$search}%");
            });
        }
        
        $repayments = $query->paginate(15);

        return view('backend.repayments.index', compact('repayments', 'status', 'search'));
    }

    /**
     * Show the form for creating a new repayment.
     */
    public function create(Request $request, $loan_id = null)
    {
        $loan_id = $loan_id ?? $request->query('loan_id');
        
        $loans = Loan::with('customer')
            ->whereIn('status', ['active', 'defaulted'])
            ->get();
            
        $selectedLoan = null;
        $nextSchedule = null;
        
        if ($loan_id) {
            $selectedLoan = Loan::with(['customer', 'product'])->find($loan_id);
            if ($selectedLoan) {
                $nextSchedule = LoanSchedule::where('loan_id', $loan_id)
                    ->where('status', '!=', 'paid')
                    ->orderBy('installment_number')
                    ->first();
            }
        }

        return view('backend.repayments.create', compact('loan_id', 'loans', 'selectedLoan', 'nextSchedule'));
    }

    /**
     * Store a newly created repayment in storage and update the loan schedule.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:191',
            'notes' => 'nullable|string'
        ]);

        $loanId = $request->loan_id;
        $payAmount = (float) $request->amount;

        DB::beginTransaction();
        try {
            // Find the oldest unpaid schedule entry
            $schedule = LoanSchedule::where('loan_id', $loanId)
                ->where('status', '!=', 'paid')
                ->orderBy('installment_number')
                ->first();

            if (!$schedule) {
                return redirect()->back()->with('error', 'Loan already fully paid or has no schedule.');
            }

            // Allocation Logic (Simplified: Principal + Interest)
            // In a real system, we might breakdown exactly what is being paid.
            // For now, we update amount_paid and status based on amount_due.
            
            $remainingRequired = (float) ($schedule->amount_due - $schedule->amount_paid);
            $newAmountPaid = (float) ($schedule->amount_paid + $payAmount);
            
            $status = 'partial';
            if ($newAmountPaid >= (float) $schedule->amount_due) {
                $status = 'paid';
            }

            // Create Repayment Record
            $repayment = Repayment::create([
                'loan_id' => $loanId,
                'schedule_id' => $schedule->id,
                'amount' => $payAmount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'status' => 'completed',
                'received_by' => Auth::id(),
                'notes' => $request->notes,
                // For now, we allocate based on the schedule's current state
                'principal_paid' => min($payAmount, $schedule->principal_due),
                'interest_paid' => max(0, min($payAmount - $schedule->principal_due, $schedule->interest_due)),
            ]);

            // Create Audit Trail Entry
            \App\Models\ActivityLog::create([
                'user_name' => Auth::user()->name,
                'action' => 'Recorded Repayment',
                'description' => "Processed payment of $" . number_format($payAmount, 2) . " for Loan [" . ($schedule->loan->loan_code ?? 'N/A') . "] (Customer: " . ($schedule->loan->customer->name ?? 'Unknown') . ")",
                'ip_address' => $request->ip(),
            ]);

            // --- 📊 AUTOMATED ACCOUNTING (JOURNAL ENTRY) ---
            $principalPaid = min($payAmount, $schedule->principal_due);
            $interestPaid = max(0, min($payAmount - $schedule->principal_due, $schedule->interest_due));

            $coaCash = \App\Models\ChartOfAccount::where('code', '1000')->first();
            $coaPrincipal = \App\Models\ChartOfAccount::where('code', '1100')->first();
            $coaInterest = \App\Models\ChartOfAccount::where('code', '4000')->first();

            $journalEntry = \App\Models\JournalEntry::create([
                'entry_date' => $request->payment_date,
                'reference_type' => 'Repayment',
                'reference_id' => $repayment->id,
                'description' => "Repayment for Loan [" . $schedule->loan->loan_code . "] - Inst #" . $schedule->installment_number,
                'total_amount' => $payAmount,
                'created_by' => Auth::id(),
            ]);

            // Debit Cash (Total)
            \App\Models\JournalItem::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_account_id' => $coaCash->id,
                'type' => 'Debit',
                'amount' => $payAmount,
            ]);

            // Credit Principal
            if ($principalPaid > 0) {
                \App\Models\JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'chart_of_account_id' => $coaPrincipal->id,
                    'type' => 'Credit',
                    'amount' => $principalPaid,
                ]);
            }

            // Credit Interest
            if ($interestPaid > 0) {
                \App\Models\JournalItem::create([
                    'journal_entry_id' => $journalEntry->id,
                    'chart_of_account_id' => $coaInterest->id,
                    'type' => 'Credit',
                    'amount' => $interestPaid,
                ]);
            }
            // ----------------------------------------------

            // Update Schedule
            $schedule->update([
                'amount_paid' => $newAmountPaid,
                'status' => $status,
                'paid_date' => $status === 'paid' ? $request->payment_date : $schedule->paid_date,
            ]);

            // If loan is fully paid, update loan status
            $unpaidSchedulesCount = LoanSchedule::where('loan_id', $loanId)
                ->where('status', '!=', 'paid')
                ->count();

            if ($unpaidSchedulesCount === 0) {
                $loan = Loan::find($loanId);
                $loan->update(['status' => 'completed']);
                
                // Clear customer's existing loan flag if they have no other active loans
                $activeCount = Loan::where('customer_id', $loan->customer_id)
                    ->where('status', 'active')
                    ->count();
                if ($activeCount === 0) {
                    $loan->customer->update(['has_existing_loan' => 0]);
                }
            }

            DB::commit();
            return redirect()->route('repayments.index')->with('success', 'ការបង់ប្រាក់ត្រូវបានរក្សាទុកដោយជោគជ័យ!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource. Not used in this context.
     */
    public function show($id)
    {
        $repayment = Repayment::with(['loan.customer', 'schedule', 'receivedBy'])->findOrFail($id);
        return view('backend.repayments.show', compact('repayment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Typically repayments are not edited after being recorded for audit purposes.
        // We'll redirect to show or index.
        return redirect()->route('repayments.index')->with('info', 'Repayments cannot be edited once recorded.');
    }
}
