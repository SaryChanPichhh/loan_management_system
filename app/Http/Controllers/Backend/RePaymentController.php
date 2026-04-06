<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Loan;
use App\Models\LoanAccount;
use App\Models\LoanSchedule;
use App\Models\Repayment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepaymentController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $query = Repayment::with(['loan.customer', 'receivedBy'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('loan', fn($q) =>
                $q->where('loan_code', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q2) =>
                      $q2->where('name', 'like', "%{$search}%")
                  )
            );
        }

        $repayments = $query->paginate(15);

        return view('backend.repayments.index', compact('repayments', 'status', 'search'));
    }

    // ─────────────────────────────────────────────────────────────────
    // CREATE — show payment form for a specific loan
    // ─────────────────────────────────────────────────────────────────

    public function create($loan_id)
    {
        $loan = Loan::with(['customer', 'product', 'schedules', 'account'])
            ->where('status', 'active')
            ->findOrFail($loan_id);

        // Next unpaid/partial installment
        $nextSchedule = $loan->schedules
            ->whereIn('status', ['pending', 'partial'])
            ->sortBy('installment_number')
            ->first();

        // Late fee calc preview
        $lateFeePreview = 0;
        $isLate = false;
        if ($nextSchedule) {
            $graceEnd = Carbon::parse($nextSchedule->grace_period_end_date ?? $nextSchedule->due_date);
            $today    = Carbon::today();
            $daysPastDue = max(0, $graceEnd->diffInDays($today, false));

            if ($daysPastDue >= 4 && $loan->account) {
                $isLate = true;
                $lateFeePreview = round((float)$loan->account->outstanding_balance * 0.015, 2);
            }
        }

        return view('backend.repayments.create', compact(
            'loan', 'nextSchedule', 'isLate', 'lateFeePreview'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // STORE — Process a repayment for loan_id
    // ─────────────────────────────────────────────────────────────────

    public function store(Request $request, $loan_id)
    {
        // ── 1. Basic validation ───────────────────────────────────────
        $request->validate([
            'amount'           => 'required|numeric|min:0.01',
            'payment_date'     => 'required|date|before_or_equal:today',
            'payment_method'   => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:191',
            'notes'            => 'nullable|string|max:1000',
        ]);

        // ── 2. Load and guard loan ────────────────────────────────────
        $loan = Loan::with(['customer', 'account'])->findOrFail($loan_id);

        if ($loan->status !== 'active') {
            return redirect()->back()
                ->with('error', 'មានតែកម្ចីដែលកំពុងដំណើរការ (active) ទើបអាចទូទាត់ (make a repayment) បាន!');
        }

        // ── 3. Find earliest pending/partial installment ──────────────
        $schedule = LoanSchedule::where('loan_id', $loan->id)
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('installment_number')
            ->lockForUpdate()
            ->first();

        if (!$schedule) {
            return redirect()->back()
                ->with('error', 'មិនមានការបង់ប្រាក់ណាមួយដែលនៅជំពាក់! (No outstanding installments found)');
        }

        // ── 4. Load loan_accounts record ──────────────────────────────
        $account = $loan->account;
        if (!$account) {
            return redirect()->back()
                ->with('error', 'រកមិនឃើញ Loan Account! (Loan account not found — please disburse first)');
        }

        // ── 5. Late fee calculation ───────────────────────────────────
        $paymentDate  = Carbon::parse($request->payment_date);
        $graceEnd     = Carbon::parse($schedule->grace_period_end_date ?? $schedule->due_date);
        $daysPastDue  = max(0, (int) $graceEnd->diffInDays($paymentDate, false));

        $lateFeeApplied = false;
        $lateFee        = 0.0;

        // Late fee applies when days past due >= 4 (matches business rules: 4–15 days = 1.5% late fee)
        if ($daysPastDue >= 4) {
            $lateFeeApplied = true;
            $lateFee        = round((float) $account->outstanding_balance * 0.015, 2);
        }

        // ── 6. Split payment into components ──────────────────────────
        $totalReceived = (float) $request->amount;
        $remaining     = $totalReceived;

        // Priority: late_fee → interest → principal → penalty
        $lateFeeActual   = 0.0;
        $interestActual  = 0.0;
        $principalActual = 0.0;
        $penaltyActual   = 0.0;

        if ($lateFeeApplied && $remaining > 0) {
            $lateFeeActual = min($lateFee, $remaining);
            $remaining    -= $lateFeeActual;
        }

        $interestDue = (float) $schedule->interest_due;
        if ($remaining > 0 && $interestDue > 0) {
            $interestActual = min($interestDue, $remaining);
            $remaining     -= $interestActual;
        }

        $principalDue = (float) $schedule->principal_due;
        if ($remaining > 0 && $principalDue > 0) {
            $principalActual = min($principalDue, $remaining);
            $remaining      -= $principalActual;
        }

        // Any leftover goes to penalty bucket (overpayment / rounding)
        if ($remaining > 0) {
            $penaltyActual = round($remaining, 2);
        }

        // ── 7. Determine new schedule status ─────────────────────────
        $amountAlreadyPaid = (float) $schedule->amount_paid;
        $newAmountPaid     = round($amountAlreadyPaid + $totalReceived - $lateFeeActual, 2);
        $totalDue          = (float) $schedule->amount_due;

        $newScheduleStatus = 'partial';
        if ($newAmountPaid >= ($totalDue - 0.01)) {
            $newScheduleStatus = 'paid';
        }

        // ── 8. Atomic transaction ─────────────────────────────────────
        DB::beginTransaction();
        try {
            // a. Insert repayment record
            $repayment = Repayment::create([
                'loan_id'          => $loan->id,
                'schedule_id'      => $schedule->id,
                'amount'           => $totalReceived,
                'principal_paid'   => $principalActual,
                'interest_paid'    => $interestActual,
                'penalty_paid'     => $penaltyActual,
                'late_fee_paid'    => $lateFeeActual,
                'late_fee_applied' => $lateFeeApplied,
                'payment_date'     => $paymentDate->toDateString(),
                'payment_method'   => $request->payment_method,
                'reference_number' => $request->reference_number,
                'status'           => 'paid',
                'notes'            => $request->notes,
                'received_by'      => Auth::id(),
            ]);

            // b. Update loan_schedules row
            $schedule->update([
                'amount_paid' => $newAmountPaid,
                'status'      => $newScheduleStatus,
                'paid_date'   => $newScheduleStatus === 'paid' ? $paymentDate->toDateString() : null,
            ]);

            // c. Recalculate outstanding_balance and update loan_accounts
            $newOutstanding = max(0, round((float) $account->outstanding_balance - $principalActual - $interestActual, 2));

            // Recalculate days_past_due based on today vs next unpaid schedule
            $nextUnpaid = LoanSchedule::where('loan_id', $loan->id)
                ->whereIn('status', ['pending', 'partial'])
                ->orderBy('installment_number')
                ->first();

            $dpd = 0;
            if ($nextUnpaid) {
                $nextGraceEnd = Carbon::parse($nextUnpaid->grace_period_end_date ?? $nextUnpaid->due_date);
                $dpd = max(0, (int) $nextGraceEnd->diffInDays(Carbon::today(), false));
            }

            $account->update([
                'outstanding_balance'  => $newOutstanding,
                'total_principal_paid' => round((float) $account->total_principal_paid + $principalActual, 2),
                'total_interest_paid'  => round((float) $account->total_interest_paid  + $interestActual, 2),
                'total_late_fee_paid'  => round((float) $account->total_late_fee_paid  + $lateFeeActual, 2),
                'total_penalty_paid'   => round((float) $account->total_penalty_paid   + $penaltyActual, 2),
                'days_past_due'        => $dpd,
                'last_payment_at'      => now(),
            ]);

            // d. Insert transaction rows (one per applicable type)
            $runningBalance = $newOutstanding;

            if ($principalActual > 0) {
                Transaction::create([
                    'account_id'      => $account->id,
                    'type'            => 'REPAYMENT_PRINCIPAL',
                    'amount'          => $principalActual,
                    'running_balance' => $runningBalance,
                    'reference'       => $repayment->reference_number,
                    'notes'           => "ទូទាត់ Principal — {$loan->loan_code} ខែ #{$schedule->installment_number}",
                    'created_by'      => Auth::id(),
                ]);
            }

            if ($interestActual > 0) {
                $runningBalance = round($runningBalance - $interestActual, 2) < 0 ? 0 : $runningBalance;
                Transaction::create([
                    'account_id'      => $account->id,
                    'type'            => 'REPAYMENT_INTEREST',
                    'amount'          => $interestActual,
                    'running_balance' => $runningBalance,
                    'reference'       => $repayment->reference_number,
                    'notes'           => "ទូទាត់ Interest — {$loan->loan_code} ខែ #{$schedule->installment_number}",
                    'created_by'      => Auth::id(),
                ]);
            }

            if ($lateFeeActual > 0) {
                Transaction::create([
                    'account_id'      => $account->id,
                    'type'            => 'REPAYMENT_LATE_FEE',
                    'amount'          => $lateFeeActual,
                    'running_balance' => $runningBalance,
                    'reference'       => $repayment->reference_number,
                    'notes'           => "ទូទាត់ Late Fee ({$daysPastDue} ថ្ងៃ) — {$loan->loan_code}",
                    'created_by'      => Auth::id(),
                ]);
            }

            if ($penaltyActual > 0) {
                Transaction::create([
                    'account_id'      => $account->id,
                    'type'            => 'REPAYMENT_PENALTY',
                    'amount'          => $penaltyActual,
                    'running_balance' => $runningBalance,
                    'reference'       => $repayment->reference_number,
                    'notes'           => "ទូទាត់ Penalty — {$loan->loan_code}",
                    'created_by'      => Auth::id(),
                ]);
            }

            // e. Check if loan is fully paid — mark completed
            $allPaid = LoanSchedule::where('loan_id', $loan->id)
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->doesntExist();

            if ($account->outstanding_balance <= 0 && $allPaid) {
                // 1. Mark loan as completed and set end_date if missing
                $loan->update([
                    'status' => 'completed',
                    'end_date' => $loan->end_date ?? now()->toDateString(),
                ]);
                
                if ($loan->customer) {
                    $loan->customer->update(['has_existing_loan' => 0]);
                }

                // 2. Release guarantors (if any) linked to this customer
                \App\Models\Guarantor::where('customer_id', $loan->customer_id)
                    ->where('status', '!=', 'released')
                    ->update(['status' => 'released']);

                // 3. Release collaterals (if any) linked to this loan
                DB::table('loan_collaterals')
                    ->where('loan_id', $loan->id)
                    ->where('status', '!=', 'released')
                    ->update(['status' => 'released']);

                // 4. Create congratulatory notification
                \App\Models\Notification::create([
                    'customer_id' => $loan->customer_id,
                    'title' => 'អបអរសាទរ! កម្ចីត្រូវបានបង់ផ្តាច់',
                    'message' => "អបអរសាទរ! កម្ចី {$loan->loan_code} របស់អ្នកត្រូវបានបង់ផ្តាច់ដោយជោគជ័យរួចរាល់។ អរគុណសម្រាប់ការប្រើប្រាស់សេវាកម្មយើងខ្ញុំ!",
                    'type' => 'LOAN_COMPLETED',
                    'is_read' => 0,
                    'target_user' => 'customer',
                ]);

                // 5. Activity log for loan completion
                $this->logActivity('LOAN_COMPLETED', "កម្ចី {$loan->loan_code} ត្រូវបានបង់ផ្តាច់ទាំងស្រុង (Fully Paid). Guarantors & Collateral released.");
            }

            // f. Activity log
            $this->logActivity(
                'REPAYMENT_RECEIVED',
                "ទទួលការបង់ប្រាក់ \${$totalReceived} សម្រាប់កម្ចី {$loan->loan_code} — ខែ #{$schedule->installment_number}"
                . ($lateFeeApplied ? " (Late Fee: \${$lateFeeActual})" : '')
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'មានបញ្ហាពេលរក្សាទុកការបង់ប្រាក់: ' . $e->getMessage());
        }

        return redirect()->route('loans.payments', $loan->id)
            ->with('success', "ការបង់ប្រាក់ \${$totalReceived} ត្រូវបានកត់ត្រាដោយជោគជ័យ! ✅"
                . ($lateFeeApplied ? " Late Fee: \${$lateFeeActual} ត្រូវបានដាក់បញ្ចូល។" : ''));
    }

    // ─────────────────────────────────────────────────────────────────
    // SHOW — repayment history for a single loan
    // ─────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $loan = Loan::with(['repayments.receivedBy', 'customer', 'schedules'])->findOrFail($id);
        return view('backend.repayments.show', compact('loan'));
    }

    // ─────────────────────────────────────────────────────────────────
    // RECEIPT
    // ─────────────────────────────────────────────────────────────────

    public function receipt($id)
    {
        $repayment = Repayment::with([
            'loan',
            'loan.customer',
            'loan.product',
            'loan.account',
            'schedule'
        ])->findOrFail($id);

        return view('backend.repayments.receipt', compact('repayment'));
    }

    // ─────────────────────────────────────────────────────────────────
    // EDIT (stub)
    // ─────────────────────────────────────────────────────────────────

    public function edit($id)
    {
        return view('backend.repayments.edit', compact('id'));
    }

    // ─────────────────────────────────────────────────────────────────
    // OVERDUE
    // ─────────────────────────────────────────────────────────────────

    public function overdue()
    {
        return view('backend.repayments.overdue');
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE
    // ─────────────────────────────────────────────────────────────────

    private function logActivity(string $action, string $description): void
    {
        try {
            ActivityLog::create([
                'user_name'   => Auth::user()?->name ?? 'System',
                'action'      => $action,
                'description' => $description,
                'ip_address'  => request()->ip(),
            ]);
        } catch (\Throwable) {
            // Non-fatal — never block the main transaction
        }
    }
}
