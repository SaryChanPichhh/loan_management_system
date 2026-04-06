<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\Customer;
use App\Models\LoanProduct;
use App\Models\Loan;
use App\Models\Guarantor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LoanApplicationController extends Controller
{
    public function index()
    {
        $applications = LoanApplication::with(['customer', 'product'])->orderBy('id', 'desc')->get();
        return view('backend.loan_applications.index', compact('applications'));
    }

    public function create()
    {
        $customers = Customer::with(['guarantors' => function ($query) {
            $query->where('status', 'active');
        }])->where('status', 1)->get();
        // UI validation: Only active products are shown
        $products = LoanProduct::active()->get();
        
        $application_code = 'APP-' . strtoupper(Str::random(6)) . time();
        return view('backend.loan_applications.create', compact('customers', 'products', 'application_code'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'application_code' => 'required|string|unique:loan_applications,application_code',
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:loan_products,id',
            'requested_amount' => 'required|numeric|min:0.01',
            'requested_months' => 'required|integer|min:1',
            'purpose' => 'nullable|string',
        ]);

        $product = LoanProduct::findOrFail($request->product_id);
        $customer = Customer::findOrFail($request->customer_id);

        $error = $this->validateApplicationBusinessRules($request, $product, $customer);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $data = $request->except('status');
        $data['status'] = 'pending';
        $data['created_by'] = Auth::id();

        LoanApplication::create($data);

        return redirect()->route('loan_applications.index')->with('success', 'បង្កើតសំណើសុំកម្ចីដោយជោគជ័យ!');
    }

    public function show($id)
    {
        $application = LoanApplication::with(['customer', 'product', 'reviewer', 'creator'])->findOrFail($id);
        return view('backend.loan_applications.show', compact('application'));
    }

    public function edit($id)
    {
        $application = LoanApplication::findOrFail($id);
        
        // Cannot edit if not pending
        if ($application->status !== 'pending') {
            return redirect()->route('loan_applications.index')->with('error', 'មានតែសំណើដែលមិនទាន់ពិនិត្យ (Pending) ប៉ុណ្ណោះដែលអាចកែប្រែបាន!');
        }

        $customers = Customer::with(['guarantors' => function ($query) {
            $query->where('status', 'active');
        }])->where('status', 1)->get();
        $products = LoanProduct::active()->get();
        return view('backend.loan_applications.edit', compact('application', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $application = LoanApplication::findOrFail($id);
        
        if ($application->status !== 'pending') {
            return redirect()->route('loan_applications.index')->with('error', 'មានតែសំណើដែលមិនទាន់ពិនិត្យ (Pending) ប៉ុណ្ណោះដែលអាចកែប្រែបាន!');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:loan_products,id',
            'requested_amount' => 'required|numeric|min:0.01',
            'requested_months' => 'required|integer|min:1',
            'purpose' => 'nullable|string',
        ]);

        $product = LoanProduct::findOrFail($request->product_id);
        $customer = Customer::findOrFail($request->customer_id);

        $error = $this->validateApplicationBusinessRules($request, $product, $customer);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $data = $request->except('status');

        $application->update($data);

        return redirect()->route('loan_applications.index')->with('success', 'កែសម្រួលសំណើសុំកម្ចីដោយជោគជ័យ!');
    }

    public function destroy($id)
    {
        $application = LoanApplication::findOrFail($id);
        
        if (in_array($application->status, ['approved', 'rejected'])) {
            return redirect()->route('loan_applications.index')->with('error', 'មិនអាចលុបសំណើដែលបានអនុម័ត ឬ បដិសេធឡើយ!');
        }

        $application->delete();
        return redirect()->route('loan_applications.index')->with('success', 'លុបសំណើសុំកម្ចីដោយជោគជ័យ!');
    }

    public function updateStatus(Request $request, $id)
    {
        $application = LoanApplication::findOrFail($id);
        $oldStatus = $application->status;
        $newStatus = $request->status;
        
        $rules = [
            'status' => 'required|in:pending,under_review,approved,rejected,cancelled',
        ];

        if ($newStatus === 'rejected') {
            $rules['rejection_reason'] = 'required|string';
        }

        if ($newStatus === 'approved') {
            $rules['approved_amount'] = 'required|numeric|min:0.01';
            $rules['approved_months'] = 'required|integer|min:1';
            $rules['start_date'] = 'required|date|after_or_equal:' . date('Y-m-01');
            $rules['end_date'] = 'required|date|after:start_date';
        }

        $request->validate($rules);

        $product = $application->product;

        // Workflow Validation
        if ($newStatus === 'under_review' && $oldStatus !== 'pending') {
            return redirect()->back()->with('error', 'មានតែសំណើដែលមិនទាន់ពិនិត្យប៉ុណ្ណោះ ទើបអាចប្តូរទៅជា កំពុងពិនិត្យ (Under Review) បាន។');
        }

        if (in_array($newStatus, ['approved', 'rejected']) && $oldStatus !== 'under_review') {
            return redirect()->back()->with('error', 'មានតែសំណើដែល កំពុងពិនិត្យ ប៉ុណ្ណោះ ទើបអាច អនុម័ត ឬ បដិសេធ បាន។');
        }
        
        // Removed submitted_at reference as requested

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            if ($newStatus === 'approved') {
                $application->reviewed_by = Auth::id();
                $application->reviewed_at = now();
                
                // Validate limits
                if ($request->approved_amount < $product->min_amount || $request->approved_amount > $product->max_amount) {
                    return redirect()->back()->with('error', 'ចំនួនប្រាក់អនុម័ត ត្រូវតែស្ថិតក្នុងចន្លោះពី $' . $product->min_amount . ' ទៅ $' . $product->max_amount);
                }
                if ($request->approved_months > $product->max_term_months || $request->approved_months < 1) {
                    return redirect()->back()->with('error', 'រយៈពេលអនុម័តអតិបរមាគឺ ' . $product->max_term_months . ' ខែ');
                }
                
                $application->start_date = $request->start_date;
                $application->end_date = $request->end_date;
            }

            if ($newStatus === 'rejected') {
                $application->reviewed_by = Auth::id();
                $application->reviewed_at = now();
                $application->rejection_reason = $request->rejection_reason;
            }

            $application->status = $newStatus;
            $application->save();

            // Auto create loan if approved
            if ($newStatus === 'approved') {
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $firstPaymentDate = $startDate->copy()->addMonth(); // Assuming monthly payments
                $gracePeriodEndDate = $firstPaymentDate->copy()->addDays($product->grace_period_days);

                $loan = new \App\Models\Loan();
                $loan->loan_code = 'LN-' . strtoupper(Str::random(6)) . time();
                $loan->customer_id = $application->customer_id;
                $loan->product_id = $application->product_id;
                $loan->application_id = $application->id;
                $loan->principal_amount = $request->approved_amount;
                $loan->interest_rate = $product->interest_rate;
                $loan->duration_months = $request->approved_months;
                
                $loan->start_date = \Carbon\Carbon::parse($request->start_date)->toDateString();
                $loan->end_date = \Carbon\Carbon::parse($request->end_date)->toDateString();
                $loan->first_payment_date = $firstPaymentDate->toDateString();
                $loan->grace_days = (int) ($product->grace_period_days ?? 3);
                $loan->status = 'approved';
                
                $loan->approved_by = Auth::id();
                $loan->created_by = Auth::id();
                $loan->purpose = $application->purpose; // Copy purpose if any
                
                // Set guarantor and collateral rules based on amounts
                $loan->guarantor_required = ($request->approved_amount > 500);
                $loan->collateral_required = ($request->approved_amount > 5000);
                
                $loan->save();

                // Update application with new loan_id
                $application->loan_id = $loan->id;
                $application->save();
            }

            \Illuminate\Support\Facades\DB::commit();

            if ($newStatus === 'under_review' && $oldStatus === 'pending') {
                return redirect()->route('loan_applications.show', $application->id)->with('success', 'ស្ថានភាពបានផ្លាស់ប្តូរទៅជា កំពុងពិនិត្យ! សូមបំពេញព័ត៌មានបន្ថែម។');
            }

            return redirect()->back()->with('success', 'មានការផ្លាស់ប្តូរដោយជោគជ័យ!');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'មានបញ្ហាអំឡុងពេលរក្សាទុក៖ ' . $e->getMessage());
        }
    }

    protected function validateApplicationBusinessRules(Request $request, $product, $customer)
    {
        $amount = $request->requested_amount;
        $months = $request->requested_months;

        if ($amount < $product->min_amount) {
            return "ចំនួនប្រាក់ស្នើសុំមិនអាចតិចជាង {$product->min_amount}$";
        }
        if ($amount > $product->max_amount) {
            return "ចំនួនប្រាក់ស្នើសុំមិនអាចច្រើនជាង {$product->max_amount}$";
        }
        if ($months <= 0 || $months > $product->max_term_months) {
            return "រយៈពេលស្នើសុំមិនត្រឹមត្រូវ (អតិបរមា: {$product->max_term_months} ខែ)";
        }
        if (!$product->status) {
            return "ផលិតផលកម្ចីនេះត្រូវបានបិទដំណើរការ";
        }

        if ($amount >= 500) {
            // Calculate estimated monthly payment
            $rate = ($product->interest_rate / 100) / 12;
            if ($rate == 0) {
                $monthlyPayment = $amount / $months;
            } else {
                $monthlyPayment = ($amount * $rate * pow(1 + $rate, $months)) / (pow(1 + $rate, $months) - 1);
            }

            $requiredCustomerIncome = $monthlyPayment * 1.5;

            // Customer must have [monthly_income >= Monthly Loan Payment × 1.5]
            if ($customer->monthly_income < $requiredCustomerIncome) {
                return "ចំណូលរបស់អតិថិជនមិនគ្រប់គ្រាន់! ត្រូវមានចំណូលយ៉ាងហោចណាស់ $" . number_format($requiredCustomerIncome, 2) . " /ខែ (ស្មើនឹង ១.៥ ដងនៃប្រាក់សងប្រចាំខែ $" . number_format($monthlyPayment, 2) . ")";
            }

            // If >= 500 and multiplier > 0, guarantor is required and must have sufficient income
            if ($amount >= 500 && $product->guarantor_income_multiplier > 0) {
                $hasGuarantor = Guarantor::where('customer_id', $customer->id)->whereIn('status', ['active'])->exists();
                if (!$hasGuarantor) {
                    return "កម្ចីទំហំចាប់ពី $500 ឡើងទៅ តម្រូវឲ្យមានអ្នកធានាសកម្មយ៉ាងហោចណាស់ម្នាក់! សូមបញ្ចូលអ្នកធានាសម្រាប់អតិថិជននេះជាមុនសិន។";
                }

                $requiredIncome = $monthlyPayment * $product->guarantor_income_multiplier;
                $qualifiedGuarantor = Guarantor::where('customer_id', $customer->id)
                    ->whereIn('status', ['active'])
                    ->where('income', '>=', $requiredIncome)
                    ->exists();

                if (!$qualifiedGuarantor) {
                    return "ចំណូលអ្នកធានាមិនគ្រប់គ្រាន់! ត្រូវមានយ៉ាងហោចណាស់ $" . number_format($requiredIncome, 2) . " /ខែ (ស្មើនឹង {$product->guarantor_income_multiplier} ដងនៃប្រាក់សងប្រចាំខែ $" . number_format($monthlyPayment, 2) . ")";
                }
            }
        }

        // Removed 5000 collateral warning as requested


        return null;
    }
}
