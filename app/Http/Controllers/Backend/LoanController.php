<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Models\LoanSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    // ─────────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $status = $request->get('status');

        $query = Loan::with(['customer', 'product', 'createdBy'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $loans = $query->get();

        // Count by status for tab badges
        $statusCounts = Loan::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('backend.loans.index', compact('loans', 'status', 'statusCounts'));
    }

    // ─────────────────────────────────────────────────────────────────
    // CREATE
    // ─────────────────────────────────────────────────────────────────

    public function create(Request $request)
    {
        // Load active customers with required eligibility fields
        $customers = Customer::where('status', 1)
            ->whereNull('deleted_at')
            ->get();

        // Load active loan products only
        $products = LoanProduct::where('status', true)->get();

        // Pre-fill from approved loan application if provided
        $application = null;
        if ($request->filled('application_id')) {
            $application = LoanApplication::with(['customer', 'product'])
                ->where('status', 'approved')
                ->findOrFail($request->application_id);
        }

        return view('backend.loans.create', compact('customers', 'products', 'application'));
    }

    // ─────────────────────────────────────────────────────────────────
    // STORE — Full Business Rule Validation
    // ─────────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        // ── Basic Laravel validation ──────────────────────────────
        $request->validate([
            'customer_id'        => 'required|exists:customers,id',
            'product_id'         => 'required|exists:loan_products,id',
            'principal_amount'   => 'required|numeric|min:1',
            'duration_months'    => 'required|integer|min:1',
            'start_date'         => 'required|date|after_or_equal:today',
            'first_payment_date' => 'required|date',
            'purpose'            => 'nullable|string|max:500',
            'disbursed_amount'   => 'nullable|numeric|min:0',
            'note'               => 'nullable|string',
            'application_id'     => 'nullable|exists:loan_applications,id',
        ]);

        $errors = [];

        // ── 1. CUSTOMER VALIDATION ────────────────────────────────
        $customer = Customer::whereNull('deleted_at')
            ->where('status', 1)
            ->findOrFail($request->customer_id);

        if (!$customer->age_verified) {
            $errors[] = 'អតិថិជនមិនទាន់ផ្ទៀងផ្ទាត់អាយុ (ត្រូវការពី 18–65 ឆ្នាំ)';
        }

        if ($customer->has_existing_loan) {
            $errors[] = 'អតិថិជនមានកម្ចីដែលកំពុងដំណើរការ — មិនអាចបង្កើតកម្ចីថ្មី (Over-lending Guard)';
        }

        if (empty($customer->document_path)) {
            $errors[] = 'អតិថិជនមិនទាន់បង្ហោះឯកសារ (National ID / KYC)';
        }

        if (empty($customer->occupation) || empty($customer->monthly_income)) {
            $errors[] = 'អតិថិជនត្រូវតែមានព័ត៌មានអំពីមុខរបរ និងចំណូលប្រចាំខែ';
        }

        if (!empty($customer->credit_score)) {
            if ($customer->credit_score < 300 || $customer->credit_score > 900) {
                $errors[] = 'Credit Score របស់អតិថិជនមិនស្ថិតក្នុងដែន 300–900';
            }
        }

        // ── 2. LOAN PRODUCT VALIDATION ────────────────────────────
        $product = LoanProduct::where('status', true)->findOrFail($request->product_id);

        $amount = (float) $request->principal_amount;
        $months = (int) $request->duration_months;

        if ($amount < (float)$product->min_amount) {
            $errors[] = "ចំនួនទឹកប្រាក់ ($" . number_format($amount, 2) . ") ទាបជាង ចំនួនអប្បបរមា ($" . number_format((float)$product->min_amount, 2) . ") របស់ Product";
        }

        if ($amount > (float)$product->max_amount) {
            $errors[] = "ចំនួនទឹកប្រាក់ ($" . number_format($amount, 2) . ") លើសចំនួនអតិបរមា ($" . number_format((float)$product->max_amount, 2) . ") របស់ Product";
        }

        if ($months > $product->max_term_months) {
            $errors[] = "រយៈពេល ($months ខែ) លើសរយៈពេលអតិបរមា ($product->max_term_months ខែ) របស់ Product";
        }

        $validInterestTypes = ['FLAT', 'REDUCING_BALANCE', 'COMPOUND'];
        if (!in_array($product->interest_type, $validInterestTypes)) {
            $errors[] = 'ប្រភេទការប្រាក់មិនត្រឹមត្រូវ';
        }

        // Credit score vs product minimum threshold
        if (!empty($customer->credit_score) && !empty($product->min_credit_score)) {
            if ($customer->credit_score < $product->min_credit_score) {
                $errors[] = "Credit Score ($customer->credit_score) ទាបជាង ច្រើនជាង ។ Product តម្រូវ $product->min_credit_score";
            }
        }

        // ── 3. GUARANTOR & COLLATERAL RULES ──────────────────────
        $guarantorRequired   = false;
        $collateralRequired  = false;
        $requiresGuarantorAbove   = $product->requires_guarantor_above  ?? 500;
        $requiresCollateralAbove  = $product->requires_collateral_above ?? 5000;

        if ($amount > $requiresGuarantorAbove) {
            $guarantorRequired = true;
            $activeGuarantor   = Guarantor::where('customer_id', $customer->id)
                ->where('status', 'active')
                ->whereNotNull('document_path')
                ->first();

            if (!$activeGuarantor) {
                $errors[] = "ចំនួនទឹកប្រាក់ ($" . number_format($amount, 2) . ") លើស $" . number_format($requiresGuarantorAbove, 2) . " — ត្រូវការអ្នកធានា (active) ដែលមានឯកសார";
            } else {
                // Guarantor national_id must be unique among active guarantors (other records)
                $duplicateGuarantor = Guarantor::where('national_id', $activeGuarantor->national_id)
                    ->where('status', 'active')
                    ->where('id', '!=', $activeGuarantor->id)
                    ->exists();
                if ($duplicateGuarantor) {
                    $errors[] = 'National ID របស់អ្នកធានា មានការប្រើប្រាស់ស្ទួននៅក្នុងកំណត់ត្រាអ្នកធានាផ្សេង';
                }
            }
        }

        if ($amount > (float)$requiresCollateralAbove) {
            $collateralRequired = true;
        }

        // ── 4. DATE RULES ─────────────────────────────────────────
        $startDate       = Carbon::parse($request->start_date);
        $gracePeriodDays = $product->grace_period_days ?? 3;
        $graceEndDate    = $startDate->copy()->addDays($gracePeriodDays);

        // Auto-compute end_date
        $endDate = $startDate->copy()->addMonths($months);

        // end_date must be after start_date (auto-computed, just guard)
        if ($endDate->lte($startDate)) {
            $errors[] = 'ថ្ងៃបញ្ចប់ត្រូវតែក្រោយថ្ងៃចាប់ផ្តើម';
        }

        // first_payment_date must be after grace_period_end_date
        $firstPaymentDate = Carbon::parse($request->first_payment_date);
        if ($firstPaymentDate->lte($graceEndDate)) {
            $errors[] = "ថ្ងៃទូទាត់ដំបូង ($firstPaymentDate->toDateString()) ត្រូវតែក្រោយ Grace Period ($graceEndDate->toDateString())";
        }

        // disbursed_amount must not exceed principal_amount
        $disbursedAmount = $request->filled('disbursed_amount') ? (float) $request->disbursed_amount : $amount;
        if ($disbursedAmount > $amount) {
            $errors[] = 'ចំនួនទឹកប្រាក់បានចាញ់ (Disbursed) មិនអាចលើសចំនួនទឹកប្រាក់កម្ចី';
        }

        // ── 5. LOAN APPLICATION LINKAGE ───────────────────────────
        $application = null;
        if ($request->filled('application_id')) {
            $application = LoanApplication::findOrFail($request->application_id);
            if ($application->status !== 'approved') {
                $errors[] = 'ពាក្យស្នើសុំត្រូវតែស្ថិតក្នុងស្ថានភាព "approved" មុនពេលបង្កើតកម្ចី';
            }
        }

        // ── Return early if errors exist ──────────────────────────
        if (!empty($errors)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['business_rules' => $errors])
                ->with('validation_errors', $errors);
        }

        // ── 6. CREATE THE LOAN ────────────────────────────────────
        DB::beginTransaction();
        try {
            // Generate next loan code
            $lastLoan = Loan::withTrashed()->orderBy('id', 'desc')->first();
            $nextNum  = $lastLoan ? intval(substr($lastLoan->loan_code, 5)) + 1 : 1;
            $loanCode = 'LOAN-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

            $loan = Loan::create([
                'loan_code'             => $loanCode,
                'customer_id'           => $customer->id,
                'product_id'            => $product->id,
                'application_id'        => $application?->id,
                'principal_amount'      => $amount,
                'disbursed_amount'      => $disbursedAmount,
                'interest_rate'         => $product->interest_rate, // snapshot
                'duration_months'       => $months,
                'status'                => 'pending',
                'purpose'               => $request->purpose,
                'note'                  => $request->note,
                'start_date'            => $startDate->toDateString(),
                'end_date'              => $endDate->toDateString(),
                'first_payment_date'    => $firstPaymentDate->toDateString(),
                'grace_period_end_date' => $graceEndDate->toDateString(),
                'collateral_required'   => $collateralRequired,
                'guarantor_required'    => $guarantorRequired,
                'created_by'            => Auth::id(),
            ]);

            // Update loan application linkage
            if ($application) {
                $application->update([
                    'loan_id'     => $loan->id,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'មានបញ្ហាពេលបង្កើតកម្ចី: ' . $e->getMessage());
        }

        return redirect()->route('loans.show', $loan->id)
            ->with('success', "កម្ចី {$loanCode} ត្រូវបានបង្កើតដោយជោគជ័យ!");
    }

    // ─────────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────────

    public function show($id)
    {
        $loan = Loan::with([
            'customer',
            'product',
            'application',
            'schedules',
            'repayments',
            'approvedBy',
            'rejectedBy',
            'createdBy',
        ])->findOrFail($id);

        // Financial calculations
        $totalPayable     = $this->calcTotalPayable($loan);
        $totalPaid        = $loan->schedules->sum('amount_paid');
        $remainingBalance = round($totalPayable - $totalPaid, 2);
        $monthlyInstalment = $this->calcMonthlyInstalment($loan);
        $totalInterest    = round($totalPayable - $loan->principal_amount, 2);

        // Guarantors for this customer
        $guarantors = $loan->customer
            ? Guarantor::where('customer_id', $loan->customer_id)->get()
            : collect();

        return view('backend.loans.show', compact(
            'loan',
            'monthlyInstalment',
            'totalInterest',
            'totalPayable',
            'totalPaid',
            'remainingBalance',
            'guarantors'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────────────

    public function edit($id)
    {
        $loan = Loan::with(['customer', 'product'])->findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->route('loans.show', $loan->id)
                ->with('error', 'អាចកែសម្រួលបានតែនៅពេលស្ថានភាព pending ប៉ុណ្ណោះ');
        }

        $customers = Customer::where('status', 1)->whereNull('deleted_at')->get();
        $products  = LoanProduct::where('status', true)->get();

        return view('backend.loans.edit', compact('loan', 'customers', 'products'));
    }

    // ─────────────────────────────────────────────────────────────────
    // UPDATE
    // ─────────────────────────────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->route('loans.show', $loan->id)
                ->with('error', 'អាចកែសម្រួលបានតែនៅពេលស្ថានភាព pending ប៉ុណ្ណោះ');
        }

        $request->validate([
            'principal_amount'   => 'required|numeric|min:1',
            'duration_months'    => 'required|integer|min:1',
            'start_date'         => 'required|date',
            'first_payment_date' => 'required|date',
            'purpose'            => 'nullable|string|max:500',
            'disbursed_amount'   => 'nullable|numeric|min:0',
            'note'               => 'nullable|string',
        ]);

        $product = $loan->product;
        $startDate   = Carbon::parse($request->start_date);
        $endDate     = $startDate->copy()->addMonths((int) $request->duration_months);
        $graceEnd    = $startDate->copy()->addDays($product->grace_period_days ?? 3);
        $disbursed   = $request->filled('disbursed_amount')
            ? (float) $request->disbursed_amount
            : (float) $request->principal_amount;

        $loan->update([
            'principal_amount'      => $request->principal_amount,
            'disbursed_amount'      => $disbursed,
            'duration_months'       => $request->duration_months,
            'start_date'            => $startDate->toDateString(),
            'end_date'              => $endDate->toDateString(),
            'first_payment_date'    => $request->first_payment_date,
            'grace_period_end_date' => $graceEnd->toDateString(),
            'purpose'               => $request->purpose,
            'note'                  => $request->note,
        ]);

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'កម្ចីត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!');
    }

    // ─────────────────────────────────────────────────────────────────
    // REVIEW
    // ─────────────────────────────────────────────────────────────────

    public function review($id)
    {
        $loan = Loan::with([
            'customer',
            'product',
            'application',
            'createdBy',
        ])->findOrFail($id);

        $customer   = $loan->customer;
        $product    = $loan->product;
        $amount     = (float) $loan->principal_amount;

        // Build eligibility checklist flags
        $checks = [
            'customer_active'       => $customer && $customer->status && !$customer->deleted_at,
            'age_verified'          => $customer && $customer->age_verified,
            'no_existing_loan'      => $customer && !$customer->has_existing_loan,
            'has_document'          => $customer && !empty($customer->document_path),
            'has_income_info'       => $customer && !empty($customer->occupation) && !empty($customer->monthly_income),
            'credit_score_ok'       => !$customer->credit_score || ($customer->credit_score >= 300 && $customer->credit_score <= 900),
            'product_active'        => $product && $product->status,
            'amount_in_range'       => $product && $amount >= $product->min_amount && $amount <= $product->max_amount,
            'term_ok'               => $product && $loan->duration_months <= $product->max_term_months,
        ];

        // Guarantors
        $guarantors = $customer
            ? Guarantor::where('customer_id', $customer->id)->get()
            : collect();

        $requiresGuarantor = $product && $amount > ($product->requires_guarantor_above ?? 500);
        $activeGuarantor   = $guarantors->where('status', 'active')->whereNotNull('document_path')->first();
        $checks['guarantor_ok'] = !$requiresGuarantor || ($activeGuarantor !== null);

        // Past loans for the same customer
        $pastLoans = Loan::where('customer_id', $loan->customer_id)
            ->where('id', '!=', $loan->id)
            ->latest()
            ->get();

        $allChecksPassed = !in_array(false, $checks, true);

        return view('backend.loans.review', compact(
            'loan',
            'customer',
            'product',
            'guarantors',
            'checks',
            'requiresGuarantor',
            'activeGuarantor',
            'pastLoans',
            'allChecksPassed'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // SUBMIT FOR REVIEW
    // ─────────────────────────────────────────────────────────────────

    public function submitForReview($id)
    {
        $loan = Loan::findOrFail($id);
        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'កម្ចីនេះមិនអាចដាក់ស្នើសុំពិនិត្យបានទេ (មានតែ pending ប៉ុណ្ណោះ)');
        }

        $loan->update(['status' => 'under_review']);

        return redirect()->back()->with('success', 'កម្ចីត្រូវបានដាក់ស្នើសុំពិនិត្យរួចរាល់');
    }

    // ─────────────────────────────────────────────────────────────────
    // APPROVE
    // ─────────────────────────────────────────────────────────────────

    public function approve($id)
    {
        $loan    = Loan::with(['product', 'customer'])->findOrFail($id);
        $product = $loan->product;

        DB::beginTransaction();
        try {
            $loan->update([
                'status'      => 'approved',
                'approved_by' => Auth::id(),
                // Snapshot interest_rate from product at approval time (only if not already set)
                'interest_rate' => $product ? $product->interest_rate : $loan->interest_rate,
            ]);

            // If linked to an application, mark as approved
            if ($loan->application_id) {
                LoanApplication::where('id', $loan->application_id)->update([
                    'status'      => 'approved',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'loan_id'     => $loan->id,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('loans.show', $loan->id)
                ->with('error', 'មានបញ្ហាពេលអនុម័ត: ' . $e->getMessage());
        }

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'កម្ចីត្រូវបានអនុម័តដោយជោគជ័យ! សូមបន្តទៅការបើកប្រាក់កម្ចី (Disbursement)។');
    }

    // ─────────────────────────────────────────────────────────────────
    // DISBURSE (Step 6: Make Loan Active)
    // ─────────────────────────────────────────────────────────────────

    public function disburse($id)
    {
        $loan = Loan::with(['product', 'customer'])->findOrFail($id);

        if ($loan->status !== 'approved') {
            return redirect()->back()->with('error', 'មានតែកម្ចីដែលបានអនុម័តរួច ទើបអាចបើកប្រាក់បាន! (approved only)');
        }

        $startDate = $loan->start_date ? Carbon::parse($loan->start_date) : Carbon::today();

        DB::beginTransaction();
        try {
            $loan->update([
                'status' => 'active',
            ]);

            // --- 📊 AUTOMATED ACCOUNTING (DISBURSEMENT JOURNAL ENTRY) ---
            $coaCash = \App\Models\ChartOfAccount::where('code', '1000')->first();
            $coaPrincipal = \App\Models\ChartOfAccount::where('code', '1100')->first();

            $journalEntry = \App\Models\JournalEntry::create([
                'entry_date' => now(), // Disbursement date
                'reference_type' => 'LoanDisbursement',
                'reference_id' => $loan->id,
                'description' => "Loan Disbursement - " . $loan->loan_code . " (Customer: " . ($loan->customer->name ?? 'Unknown') . ")",
                'total_amount' => $loan->principal_amount,
                'created_by' => Auth::id(),
            ]);

            // Debit Principal Receivable (Increase Asset)
            \App\Models\JournalItem::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_account_id' => $coaPrincipal->id,
                'type' => 'Debit',
                'amount' => $loan->principal_amount,
            ]);

            // Credit Cash (Decrease Asset)
            \App\Models\JournalItem::create([
                'journal_entry_id' => $journalEntry->id,
                'chart_of_account_id' => $coaCash->id,
                'type' => 'Credit',
                'amount' => $loan->principal_amount,
            ]);
            // -------------------------------------------------------------

            // Generate loan schedules
            $this->generateSchedule($loan, $startDate);

            // Mark customer as having an existing loan
            if ($loan->customer) {
                $loan->customer->update(['has_existing_loan' => 1]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('loans.show', $loan->id)
                ->with('error', 'មានបញ្ហាពេលបើកប្រាក់កម្ចី: ' . $e->getMessage());
        }

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'កម្ចីត្រូវបានបើកប្រាក់ជោគជ័យ សកម្ម និងកាលវិភាគទូទាត់ត្រូវបានបង្កើត!');
    }

    // ─────────────────────────────────────────────────────────────────
    // REJECT
    // ─────────────────────────────────────────────────────────────────

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string|min:5|max:1000',
        ]);

        $loan = Loan::findOrFail($id);

        DB::beginTransaction();
        try {
            $loan->update([
                'status'          => 'rejected',
                'rejected_by'     => Auth::id(),
                'rejected_reason' => $request->rejected_reason,
            ]);

            // If linked to an application, mark it rejected too
            if ($loan->application_id) {
                LoanApplication::where('id', $loan->application_id)->update([
                    'status'           => 'rejected',
                    'rejection_reason' => $request->rejected_reason,
                    'reviewed_by'      => Auth::id(),
                    'reviewed_at'      => now(),
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('loans.review', $loan->id)
                ->with('error', 'មានបញ្ហាពេលបដិសេធ: ' . $e->getMessage());
        }

        return redirect()->route('loans.show', $loan->id)
            ->with('success', 'កម្ចីត្រូវបានបដិសេធ ហើយមូលហេតុត្រូវបានកត់ត្រា។');
    }

    // ─────────────────────────────────────────────────────────────────
    // PAYMENTS
    // ─────────────────────────────────────────────────────────────────

    public function payments($id)
    {
        $loan = Loan::with(['customer', 'product', 'schedules', 'repayments'])->findOrFail($id);

        $totalPaid        = $loan->schedules->sum('amount_paid');
        $totalPayable     = $this->calcTotalPayable($loan);
        $remainingBalance = round($totalPayable - $totalPaid, 2);
        $overdueCount     = $loan->schedules
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', now()->toDateString())
            ->count();

        return view('backend.loans.payments', compact(
            'loan',
            'totalPaid',
            'remainingBalance',
            'overdueCount',
            'totalPayable'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // DEFAULTED
    // ─────────────────────────────────────────────────────────────────

    public function defaulted()
    {
        $defaultedLoans = Loan::with(['customer', 'product', 'schedules'])
            ->whereIn('status', ['defaulted', 'written_off'])
            ->get()
            ->map(function (Loan $loan) {
                $totalPayable            = $this->calcTotalPayable($loan);
                $totalPaid               = $loan->schedules->sum('amount_paid');
                $loan->remaining_balance = round($totalPayable - $totalPaid, 2);

                $firstUnpaid       = $loan->schedules
                    ->where('status', '!=', 'paid')
                    ->sortBy('due_date')
                    ->first();
                $loan->overdue_days = $firstUnpaid
                    ? Carbon::parse($firstUnpaid->due_date)->diffInDays(now())
                    : 0;

                return $loan;
            });

        return view('backend.loans.defaulted', compact('defaultedLoans'));
    }

    // ─────────────────────────────────────────────────────────────────
    // CALENDAR
    // ─────────────────────────────────────────────────────────────────

    public function calendar()
    {
        // Get today's payments
        $today = Carbon::today();
        $todayPayments = LoanSchedule::with(['loan.customer'])
            ->whereDate('due_date', $today)
            ->orderBy('loan_id')
            ->get();

        // Get calendar events for the next 3 months
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addMonths(3)->endOfMonth();

        $calendarEvents = [];

        // Group payments by date
        $paymentsByDate = LoanSchedule::selectRaw('DATE(due_date) as payment_date, COUNT(*) as payment_count, SUM(amount_due) as total_amount, SUM(amount_paid) as paid_amount')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->groupBy('payment_date')
            ->orderBy('payment_date')
            ->get();

        foreach ($paymentsByDate as $paymentGroup) {
            $date = $paymentGroup->payment_date;
            $count = $paymentGroup->payment_count;
            $totalAmount = $paymentGroup->total_amount;
            $paidAmount = $paymentGroup->paid_amount;

            // Determine color based on payment status
            $unpaidAmount = $totalAmount - $paidAmount;
            if ($unpaidAmount == 0) {
                $color = '#28a745'; // Green - all paid
            } elseif ($paidAmount > 0) {
                $color = '#ffc107'; // Yellow - partial payment
            } else {
                $color = '#dc3545'; // Red - no payment
            }

            $calendarEvents[] = [
                'title' => $count . ' payments',
                'date' => $date,
                'count' => $count,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'color' => $color,
            ];
        }

        return view('backend.loans.calendar', compact('todayPayments', 'calendarEvents'));
    }

    // ─────────────────────────────────────────────────────────────────
    // REPAYMENTS DUE TODAY
    // ─────────────────────────────────────────────────────────────────

    public function repaymentsDueToday(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $query = LoanSchedule::whereDate('due_date', $today)
            ->where('status', '!=', 'paid')
            ->with(['loan.customer', 'loan.product']);

        if ($request->ajax()) {
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->whereHas('loan.customer', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                })->orWhereHas('loan', function($q) use ($search) {
                    $q->where('loan_code', 'like', "%{$search}%");
                });
                
                // Re-enforce today's date and unpaid status after nested orWhere
                $query->whereDate('due_date', $today)->where('status', '!=', 'paid');
            }
            $schedules = $query->get();
            return view('backend.loans.partials.repayments_table', compact('schedules'))->render();
        }

        $schedules = $query->get();
        return view('backend.loans.repayments_due_today', compact('schedules'));
    }

    // ─────────────────────────────────────────────────────────────────
    // DESTROY (Soft Delete)
    // ─────────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status === 'active') {
            return redirect()->back()
                ->with('error', 'មិនអាចលុបកម្ចីដែលកំពុងដំណើរការ — សូមធ្វើ write-off ជំនួស');
        }

        $loan->delete(); // Soft delete via SoftDeletes trait

        return redirect()->route('loans.index')
            ->with('success', 'កម្ចីត្រូវបានលុប (Soft Delete) ដោយជោគជ័យ!');
    }

    // ─────────────────────────────────────────────────────────────────
    // AJAX: Customer Eligibility Check
    // ─────────────────────────────────────────────────────────────────

    public function checkCustomerEligibility(Request $request)
    {
        $customer = Customer::with('guarantors')
            ->whereNull('deleted_at')
            ->find($request->customer_id);

        if (!$customer) {
            return response()->json(['eligible' => false, 'errors' => ['អតិថិជនមិនមាន']]);
        }

        $issues = [];

        if (!$customer->status)          $issues[] = 'អតិថិជនមិន Active';
        if (!$customer->age_verified)    $issues[] = 'មិនទាន់ផ្ទៀងផ្ទាត់អាយុ';
        if ($customer->has_existing_loan) $issues[] = 'មានកម្ចីកំពុងដំណើរការ';
        if (empty($customer->document_path)) $issues[] = 'គ្មានឯកសារ KYC';
        if (empty($customer->occupation) || empty($customer->monthly_income)) $issues[] = 'គ្មានព័ត៌មានមុខរបរ/ចំណូល';
        if ($customer->credit_score && ($customer->credit_score < 300 || $customer->credit_score > 900)) {
            $issues[] = 'Credit Score ($customer->credit_score) ខុស (300–900)';
        }

        return response()->json([
            'eligible'       => empty($issues),
            'errors'         => $issues,
            'occupation'     => $customer->occupation,
            'monthly_income' => $customer->monthly_income,
            'credit_score'   => $customer->credit_score,
            'age_verified'   => $customer->age_verified,
            'has_document'   => !empty($customer->document_path),
            'guarantors_count' => $customer->guarantors->where('status', 'active')->count(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────

    private function calcMonthlyInstalment(Loan $loan): float
    {
        if (!$loan->duration_months) return 0.0;
        $rate = ($loan->interest_rate / 100) / 12;
        if ($rate == 0) {
            return round($loan->principal_amount / $loan->duration_months, 2);
        }
        return round(
            ($loan->principal_amount * $rate * pow(1 + $rate, $loan->duration_months))
                / (pow(1 + $rate, $loan->duration_months) - 1),
            2
        );
    }

    private function calcTotalPayable(Loan $loan): float
    {
        return round($this->calcMonthlyInstalment($loan) * $loan->duration_months, 2);
    }

    private function generateSchedule(Loan $loan, Carbon $startDate): void
    {
        // Delete any existing schedule first
        LoanSchedule::where('loan_id', $loan->id)->delete();

        $monthlyInstalment = $this->calcMonthlyInstalment($loan);
        $firstPayDate      = $loan->first_payment_date
            ? Carbon::parse($loan->first_payment_date)
            : $startDate->copy()->addMonth();

        for ($month = 1; $month <= $loan->duration_months; $month++) {
            $dueDate = $firstPayDate->copy()->addMonths($month - 1);

            LoanSchedule::create([
                'loan_id'     => $loan->id,
                'due_date'    => $dueDate->toDateString(),
                'amount_due'  => $monthlyInstalment,
                'amount_paid' => 0,
                'status'      => 'pending',
            ]);
        }
    }
}
