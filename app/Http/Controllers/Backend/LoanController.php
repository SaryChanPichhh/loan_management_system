<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\LoanAccount;
use App\Models\LoanApplication;
use App\Models\LoanDisbursement;
use App\Models\LoanProduct;
use App\Models\LoanSchedule;
use App\Models\Transaction;
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

        $query = Loan::with(['customer', 'product', 'createdBy', 'account'])
            ->latest();

        if ($status === 'overdue') {
            $query->where('status', 'active')
                  ->whereHas('account', function($q) {
                      $q->where('days_past_due', '>', 0);
                  });
        } elseif ($status) {
            $query->where('status', $status);
        }

        $loans = $query->get();

        // Count by status for tab badges
        $statusCounts = Loan::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Custom count for overdue
        $statusCounts['overdue'] = Loan::where('status', 'active')
            ->whereHas('account', function($q) {
                $q->where('days_past_due', '>', 0);
            })->count();

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
        $requiresCollateralAbove  = $product->requires_collateral_above ?? 5000;

        if ($amount >= 500) {
            // Calculate estimated monthly payment
            $rate = ($product->interest_rate / 100) / 12;
            $monthlyPayment = $rate == 0 ? ($amount / $months) : ($amount * $rate * pow(1 + $rate, $months)) / (pow(1 + $rate, $months) - 1);
            $requiredCustomerIncome = $monthlyPayment * 1.5;

            if ($customer->monthly_income < $requiredCustomerIncome) {
                $errors[] = "ចំណូលរបស់អតិថិជនមិនគ្រប់គ្រាន់! ត្រូវមានយ៉ាងហោចណាស់ $" . number_format($requiredCustomerIncome, 2) . " /ខែ";
            }

            if ($amount >= 500 && $product->guarantor_income_multiplier > 0) {
                $guarantorRequired = true;
                $activeGuarantor = Guarantor::where('customer_id', $customer->id)
                    ->whereIn('status', ['active'])
                    ->first();

                if (!$activeGuarantor) {
                    $errors[] = "កម្ចីទំហំចាប់ពី $500 ឡើងទៅ តម្រូវឲ្យមានអ្នកធានាសកម្មយ៉ាងហោចណាស់ម្នាក់!";
                } else {
                    // Check duplicate
                    $duplicateGuarantor = Guarantor::where('national_id', $activeGuarantor->national_id)
                        ->where('status', 'active')
                        ->where('id', '!=', $activeGuarantor->id)
                        ->exists();
                    if ($duplicateGuarantor) {
                        $errors[] = 'National ID របស់អ្នកធានា មានការប្រើប្រាស់ស្ទួននៅក្នុងកំណត់ត្រាអ្នកធានាផ្សេង';
                    }

                    // Check income
                    $requiredIncome = $monthlyPayment * $product->guarantor_income_multiplier;
                    if ($activeGuarantor->income < $requiredIncome) {
                        $errors[] = "ចំណូលអ្នកធានាមិនគ្រប់គ្រាន់! ត្រូវមានយ៉ាងហោចណាស់ $" . number_format($requiredIncome, 2) . " /ខែ";
                    }
                    if (empty($activeGuarantor->document_path)) {
                        $errors[] = "អ្នកធានាត្រូវតែមានឯកសារភ្ជាប់ (Document Path)";
                    }
                }
            }
        }

        if ($amount > (float)$requiresCollateralAbove) {
            $collateralRequired = true;
        }

        // ── 4. DATE RULES ─────────────────────────────────────────
        $startDate       = Carbon::parse($request->start_date);
        $gracePeriodDays = (int) ($product->grace_period_days ?? 3);
        $graceEndDate    = $startDate->copy()->addDays($gracePeriodDays);

        // Auto-compute end_date
        $endDate = $startDate->copy()->addMonths($months);

        // end_date must be after start_date (auto-computed, just guard)
        if ($endDate->lte($startDate)) {
            $errors[] = 'ថ្ងៃបញ្ចប់ត្រូវតែក្រោយថ្ងៃចាប់ផ្តើម';
        }

        // first_payment_date must be after grace_period_end_date
        $firstPaymentDate = Carbon::parse($request->first_payment_date);

        // disbursed_amount must not exceed principal_amount
        $disbursedAmount = $request->filled('disbursed_amount') ? (float) $request->disbursed_amount : null;
        if ($disbursedAmount !== null && $disbursedAmount > $amount) {
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
                'status'                => 'approved',
                'purpose'               => $request->purpose,
                'note'                  => $request->note,
                'start_date'            => $startDate->toDateString(),
                'end_date'              => $endDate->toDateString(),
                'first_payment_date'    => $firstPaymentDate->toDateString(),
                'grace_days'            => $gracePeriodDays,
                'collateral_required'   => $collateralRequired,
                'guarantor_required'    => $guarantorRequired,
                'created_by'            => Auth::id(),
                'approved_by'           => Auth::id(),
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

        // Collateral status for warning badge
        $principal            = (float) $loan->principal_amount;
        $activeCollateralValue = (float) DB::table('loan_collaterals')
            ->where('loan_id', $loan->id)
            ->where('status', 'active')
            ->sum('estimated_value');
        $requiredCollateralValue = round($principal * 1.20, 2);
        $collateralInsufficient  = $principal > 5000 && $activeCollateralValue < $requiredCollateralValue;

        return view('backend.loans.show', compact(
            'loan', 'monthlyInstalment', 'totalInterest',
            'totalPayable', 'totalPaid', 'remainingBalance',
            'guarantors', 'collateralInsufficient'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // EDIT
    // ─────────────────────────────────────────────────────────────────

    public function edit($id)
    {
        $loan = Loan::with(['customer', 'product'])->findOrFail($id);

        if ($loan->status !== 'approved') {
            return redirect()->route('loans.show', $loan->id)
                ->with('error', 'អាចកែសម្រួលបានតែនៅពេលស្ថានភាព approved ប៉ុណ្ណោះ');
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

        if ($loan->status !== 'approved') {
            return redirect()->route('loans.show', $loan->id)
                ->with('error', 'អាចកែសម្រួលបានតែនៅពេលស្ថានភាព approved ប៉ុណ្ណោះ');
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
        $graceDays   = (int) ($product->grace_period_days ?? 3);
        $disbursed   = $request->filled('disbursed_amount') ? (float) $request->disbursed_amount : null;

        $loan->update([
            'principal_amount'      => $request->principal_amount,
            'disbursed_amount'      => $disbursed,
            'duration_months'       => $request->duration_months,
            'start_date'            => $startDate->toDateString(),
            'end_date'              => $endDate->toDateString(),
            'first_payment_date'    => $request->first_payment_date,
            'grace_days'            => $graceDays,
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
            'loan', 'customer', 'product', 'guarantors', 'checks',
            'requiresGuarantor', 'activeGuarantor', 'pastLoans', 'allChecksPassed'
        ));
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
    // DISBURSE (Step 3: Make Loan Active + Full Accounting Setup)
    // ─────────────────────────────────────────────────────────────────

    public function showDisburseForm($id)
    {
        $loan = Loan::with('customer')->findOrFail($id);

        if ($loan->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'Only approved loans can be disbursed!');
        }

        $requiresCollateral = ((float) $loan->principal_amount > 5000);
        $hasCollateral = false;

        if ($requiresCollateral) {
            $hasCollateral = DB::table('loan_collaterals')
                ->where('loan_id', $loan->id)
                ->where('status', 'active')
                ->exists();
        }

        return view('backend.loans.disburse', compact('loan', 'requiresCollateral', 'hasCollateral'));
    }

    public function disburse(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:BANK_TRANSFER,CASH,MOBILE_MONEY,CHEQUE',
            'reference_number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:200',
            'account_number' => 'nullable|string|max:100',
            'disbursed_at' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $loan = Loan::with(['product', 'customer'])->findOrFail($id);

        // ── Guard: only approved loans may be disbursed ───────────────
        if ($loan->status !== 'approved') {
            return redirect()->back()
                ->with('error', 'មានតែកម្ចីដែលបានអនុម័តរួច ទើបអាចបើកប្រាក់បាន! (approved only)');
        }

        // Removed 5000 collateral guard as requested


        $principal   = (float) $loan->principal_amount;
        $months      = (int)   $loan->duration_months;
        $disbursedAmt = (float) $request->amount;
        $startDate   = $loan->start_date ? Carbon::parse($loan->start_date) : Carbon::parse($request->disbursed_at);
        $endDate     = $startDate->copy()->addMonths($months);
        $firstPayDate = $loan->first_payment_date
            ? Carbon::parse($loan->first_payment_date)
            : $startDate->copy()->addMonth();

        DB::beginTransaction();
        try {
            // ── 1. Update loan record ──────────────────────────────────
            $loan->update([
                'status'           => 'active',
                'disbursed_amount' => $disbursedAmt,
                'start_date'       => $startDate->toDateString(),
                'end_date'         => $endDate->toDateString(),
                'first_payment_date' => $firstPayDate->toDateString(),
            ]);

            // ── 2. Insert loan_disbursements record ───────────────────
            LoanDisbursement::create([
                'loan_id'          => $loan->id,
                'amount'           => $disbursedAmt,
                'method'           => $request->input('method'),
                'reference_number' => $request->reference_number ?? strtoupper('DISB-' . $loan->loan_code . '-' . now()->format('YmdHis')),
                'bank_name'        => $request->bank_name,
                'account_number'   => $request->account_number,
                'notes'            => $request->notes ?? 'ការបើកប្រាក់កម្ចី',
                'disbursed_at'     => $request->disbursed_at,
                'disbursed_by'     => Auth::id(),
            ]);

            // ── 3. Create loan_accounts record ────────────────────────
            $totalInterest = $this->calcTotalInterest($principal, $months);
            $account = LoanAccount::create([
                'loan_id'             => $loan->id,
                'account_number'      => 'ACC-' . strtoupper($loan->loan_code) . '-' . now()->format('Ymd'),
                'outstanding_balance' => round($principal + $totalInterest, 2),
            ]);

            // ── 4. Insert DISBURSEMENT transaction ────────────────────
            Transaction::create([
                'account_id'      => $account->id,
                'type'            => 'DISBURSEMENT',
                'amount'          => $disbursedAmt,
                'running_balance' => round($principal + $totalInterest, 2),
                'reference'       => $account->account_number,
                'notes'           => 'ការបើកប្រាក់កម្ចី ' . $loan->loan_code,
                'created_by'      => Auth::id(),
            ]);

            // ── 5. Generate repayment schedule ────────────────────────
            $this->generateSchedule($loan, $startDate, $firstPayDate);

            // ── 6. Mark customer as having an existing loan ───────────
            if ($loan->customer) {
                $loan->customer->update(['has_existing_loan' => 1]);
            }

            // ── 7. Activity log ───────────────────────────────────────
            $this->logActivity(
                'LOAN_DISBURSED',
                "បើកប្រាក់កម្ចី {$loan->loan_code} ចំនួន \${$disbursedAmt} ដល់ {$loan->customer->name}"
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('loans.show', $loan->id)
                ->with('error', 'មានបញ្ហាពេលបើកប្រាក់កម្ចី: ' . $e->getMessage());
        }

        return redirect()->route('loans.show', $loan->id)
            ->with('success', "កម្ចី {$loan->loan_code} ត្រូវបានបើកប្រាក់ជោគជ័យ! ✅ កាលវិភាគបង់ {$months} ខែ ត្រូវបានបង្កើត។");
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
    // EARLY SETTLEMENT
    // ─────────────────────────────────────────────────────────────────

    public function earlySettle(Request $request, $id)
    {
        $loan = Loan::with(['account'])->findOrFail($id);

        if ($loan->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Only active loans can be settled early!');
        }

        $account = $loan->account;
        if (!$account) {
            return redirect()->back()
                ->with('error', 'Loan account not found!');
        }

        $balance = (float) $account->outstanding_balance;
        if ($balance <= 0) {
            return redirect()->back()
                ->with('error', 'This loan has no outstanding balance.');
        }

        DB::beginTransaction();
        try {
            // 1. Record full repayment
            $repayment = \App\Models\Repayment::create([
                'loan_id' => $loan->id,
                'amount' => $balance,
                'principal_paid' => $balance,
                'interest_paid' => 0,
                'penalty_paid' => 0,
                'late_fee_paid' => 0,
                'is_early_settlement' => true,
                'payment_date' => now()->toDateString(),
                'payment_method' => $request->input('payment_method', 'Cash'),
                'reference_number' => $request->input('reference_number'),
                'status' => 'paid',
                'notes' => 'Early Settlement: Paid full outstanding balance.',
                'received_by' => Auth::id(),
            ]);

            // 2. Update remaining schedules
            \App\Models\LoanSchedule::where('loan_id', $loan->id)
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->update(['status' => 'waived']);

            // 3. Set outstanding balance to 0 and update aggregates
            $account->update([
                'outstanding_balance' => 0,
                'total_principal_paid' => $account->total_principal_paid + $balance,
                'last_payment_at' => now(),
            ]);

            // 4. Mark loan as completed
            $loan->update([
                'status' => 'completed',
                'early_settlement_date' => now()->toDateString(),
            ]);

            if ($loan->customer) {
                $loan->customer->update(['has_existing_loan' => 0]);
            }

            // 5. Insert transaction record
            \App\Models\Transaction::create([
                'account_id' => $account->id,
                'type' => 'REPAYMENT_PRINCIPAL',
                'amount' => $balance,
                'running_balance' => 0,
                'reference' => $repayment->reference_number ?? 'SETTLE-'.$loan->loan_code,
                'notes' => "Early Settlement for Loan {$loan->loan_code}",
                'created_by' => Auth::id(),
            ]);

            // 6. Log it
            $this->logActivity('EARLY_SETTLEMENT', "Loan {$loan->loan_code} was settled early with a payment of \${$balance}.");

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error processing early settlement: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Loan has been successfully settled early and is now completed!');
    }

    // ─────────────────────────────────────────────────────────────────
    // MARK DEFAULTED
    // ─────────────────────────────────────────────────────────────────

    public function markDefault($id)
    {
        $loan = Loan::with(['account', 'customer'])->findOrFail($id);

        if ($loan->status !== 'active') {
            return redirect()->back()->with('error', 'Only active loans can be marked as defaulted.');
        }

        if (!$loan->account || $loan->account->days_past_due <= 30) {
            return redirect()->back()->with('error', 'Loan cannot be marked defaulted unless it is strictly more than 30 days past due.');
        }

        DB::beginTransaction();
        try {
            // 1. Update loan status
            $loan->update(['status' => 'defaulted']);

            // 2. Update guarantors status
            \App\Models\Guarantor::where('customer_id', $loan->customer_id)
                ->where('status', '!=', 'released')
                ->update(['status' => 'defaulted']);

            // 3. Create Notification
            \App\Models\Notification::create([
                'customer_id' => $loan->customer_id,
                'title' => 'URGENT: Legal Proceedings Required',
                'message' => "កម្ចី {$loan->loan_code} ត្រូវបានកំណត់ជាកម្ចីខូច (Defaulted) ដោយសារហួសកំណត់ {$loan->account->days_past_due} ថ្ងៃ។ សូមចាប់ផ្តើមនីតិវិធីច្បាប់ (Legal Proceedings) និងទាក់ទងអ្នកធានា។",
                'type' => 'LEGAL_PROCEEDING',
                'is_read' => 0,
                'target_user' => 'admin',
            ]);

            // 4. Log
            $this->logActivity('LOAN_DEFAULTED', "កម្ចី {$loan->loan_code} ត្រូវបានកំណត់ជា Defaulted ព្រោះហួសកំណត់ {$loan->account->days_past_due} ថ្ងៃ។");

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'មានបញ្ហាពេលកំណត់ Defaulted: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "កម្ចី {$loan->loan_code} ត្រូវបានកំណត់ជា Defaulted ជោគជ័យ! ប្រព័ន្ធបានជូនដំណឹងសម្រាប់ការចាត់វិធានការផ្លូវច្បាប់។");
    }

    // ─────────────────────────────────────────────────────────────────
    // WRITE OFF
    // ─────────────────────────────────────────────────────────────────

    public function writeOff($id)
    {
        $loan = Loan::with(['account', 'customer'])->findOrFail($id);

        if ($loan->status !== 'defaulted') {
            return redirect()->back()->with('error', 'Only defaulted loans can be written off.');
        }

        $account = $loan->account;
        if (!$account) {
            return redirect()->back()->with('error', 'Loan account not found!');
        }

        $balance = (float) $account->outstanding_balance;

        DB::beginTransaction();
        try {
            // 1. Update loan status
            $loan->update(['status' => 'written_off']);

            if ($loan->customer) {
                // Free the customer lock since the loan is terminally closed
                $loan->customer->update(['has_existing_loan' => 0]);
            }

            // 2. Insert write-off transaction
            if ($balance > 0) {
                \App\Models\Transaction::create([
                    'account_id' => $account->id,
                    'type' => 'WRITE_OFF',
                    'amount' => $balance,
                    'running_balance' => 0,
                    'reference' => 'WRITEOFF-' . $loan->loan_code,
                    'notes' => "Loan {$loan->loan_code} written off. Bad debt.",
                    'created_by' => Auth::id(),
                ]);
            }

            // 3. Clear account balance
            $account->update(['outstanding_balance' => 0]);

            // 4. Waive remaining schedules
            \App\Models\LoanSchedule::where('loan_id', $loan->id)
                ->whereIn('status', ['pending', 'partial', 'overdue'])
                ->update(['status' => 'waived']);

            // 5. Seize collateral
            if ($loan->collateral_required) {
                DB::table('loan_collaterals')
                    ->where('loan_id', $loan->id)
                    ->where('status', 'active')
                    ->update(['status' => 'seized']);
            }

            // 6. Log it
            $this->logActivity('LOAN_WRITTEN_OFF', "កម្ចី {$loan->loan_code} ត្រូវបានកត់ត្រាជាបំណុលខូច (Written Off) ក្នុងទំហំទឹកប្រាក់ \${$balance} ហើយទ្រព្យបញ្ចាំត្រូវបានរឹបអូស។");

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'មានបញ្ហាពេលដំណើរការ Write off: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', "កម្ចី {$loan->loan_code} ត្រូវបានលុបចេញពីបញ្ជីបំណុល (Written Off) ជោគជ័យចំណែកឯទ្រព្យបញ្ចាំត្រូវបានរឹបអូស។");
    }
    // ─────────────────────────────────────────────────────────────────
    // PAYMENTS
    // ─────────────────────────────────────────────────────────────────

    public function payments($id)
    {
        $loan = Loan::with([
            'customer',
            'product',
            'schedules',
            'repayments.receivedBy',
            'account',
        ])->findOrFail($id);

        $totalPaid        = $loan->schedules->sum('amount_paid');
        $totalPayable     = $this->calcTotalPayable($loan);
        $remainingBalance = round($totalPayable - $totalPaid, 2);
        $overdueCount     = $loan->schedules
            ->whereNotIn('status', ['paid'])
            ->filter(fn($s) => \Carbon\Carbon::parse($s->grace_period_end_date ?? $s->due_date)->lt(now()))
            ->count();

        return view('backend.loans.payments', compact(
            'loan', 'totalPaid', 'remainingBalance', 'overdueCount', 'totalPayable'
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // PRINT SCHEDULE
    // ─────────────────────────────────────────────────────────────────

    public function printSchedule($id)
    {
        $loan = Loan::with([
            'customer',
            'product',
            'schedules' => function ($query) {
                $query->orderBy('installment_number', 'asc');
            },
            'account'
        ])->findOrFail($id);

        $totalAmountPayable = $loan->schedules->sum('amount_due');
        $totalInterest      = $loan->schedules->sum('interest_due');
        $totalPrincipal     = $loan->schedules->sum('principal_due');

        return view('backend.loans.schedule_print', compact('loan', 'totalAmountPayable', 'totalInterest', 'totalPrincipal'));
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
                    ->sortBy('grace_period_end_date')
                    ->first();
                $loan->overdue_days = $firstUnpaid
                    ? Carbon::parse($firstUnpaid->grace_period_end_date)->diffInDays(now())
                    : 0;

                return $loan;
            });

        return view('backend.loans.defaulted', compact('defaultedLoans'));
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
            'guarantors'     => $customer->guarantors->where('status', 'active')->map(function($g) {
                return [
                    'id' => $g->id,
                    'full_name' => $g->full_name,
                    'income' => $g->income,
                    'relationship' => $g->relationship
                ];
            })->values(),
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

    private function generateSchedule(Loan $loan, Carbon $startDate, ?Carbon $firstPayDate = null): void
    {
        // Delete any existing schedule rows first (idempotent)
        LoanSchedule::where('loan_id', $loan->id)->delete();

        $principal    = (float) $loan->principal_amount;
        $months       = (int)   $loan->duration_months;
        $graceDays    = (int)  ($loan->grace_days ?? 0);
        $monthlyRate  = $this->flatMonthlyRate($months);  // e.g. 0.03 for 3%

        // Flat-rate: total interest spread evenly over all installments
        $monthlyInterest  = round($principal * $monthlyRate, 2);
        $monthlyPrincipal = round($principal / $months, 2);
        $monthlyTotal     = round($monthlyPrincipal + $monthlyInterest, 2);

        $payDate = $firstPayDate ?? ($loan->first_payment_date
            ? Carbon::parse($loan->first_payment_date)
            : $startDate->copy()->addMonth());

        for ($i = 1; $i <= $months; $i++) {
            $dueDate      = $payDate->copy()->addMonths($i - 1);
            $graceEndDate = $dueDate->copy()->addDays($graceDays);

            // Last installment absorbs rounding remainder
            $principalDue = ($i === $months)
                ? round($principal - ($monthlyPrincipal * ($months - 1)), 2)
                : $monthlyPrincipal;

            LoanSchedule::create([
                'loan_id'               => $loan->id,
                'installment_number'    => $i,
                'due_date'              => $dueDate->toDateString(),
                'principal_due'         => $principalDue,
                'interest_due'          => $monthlyInterest,
                'penalty_due'           => 0,
                'late_fee_due'          => 0,
                'amount_due'            => round($principalDue + $monthlyInterest, 2),
                'amount_paid'           => 0,
                'status'                => 'pending',
                'grace_period_end_date' => $graceEndDate->toDateString(),
            ]);
        }
    }

    /**
     * Tiered flat monthly interest rate based on loan duration.
     *
     * 1–3  months  → 3.0% / month
     * 4–6  months  → 2.5% / month
     * 7–12 months  → 2.0% / month
     * >12  months  → 1.5% / month
     */
    private function flatMonthlyRate(int $months): float
    {
        return match (true) {
            $months <= 3  => 0.030,
            $months <= 6  => 0.025,
            $months <= 12 => 0.020,
            default       => 0.015,
        };
    }

    /**
     * Total interest for the full loan (flat method).
     */
    private function calcTotalInterest(float $principal, int $months): float
    {
        return round($principal * $this->flatMonthlyRate($months) * $months, 2);
    }

    /**
     * Write a row to activity_logs.
     */
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
