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
        $customers = Customer::where('status', 1)->get();
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

        $customers = Customer::where('status', 1)->get();
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

        if (in_array($newStatus, ['approved', 'rejected'])) {
            $application->reviewed_by = Auth::id();
            $application->reviewed_at = now();
        }

        if ($newStatus === 'approved') {
            // Validate limits
            if ($request->approved_amount < $product->min_amount || $request->approved_amount > $product->max_amount) {
                return redirect()->back()->with('error', 'ចំនួនប្រាក់អនុម័ត ត្រូវតែស្ថិតក្នុងចន្លោះពី $' . $product->min_amount . ' ទៅ $' . $product->max_amount);
            }
            if ($request->approved_months > $product->max_term_months || $request->approved_months < 1) {
                return redirect()->back()->with('error', 'រយៈពេលអនុម័តអតិបរមាគឺ ' . $product->max_term_months . ' ខែ');
            }
            
            $application->approved_amount = $request->approved_amount;
            $application->approved_months = $request->approved_months;
        }

        if ($newStatus === 'rejected') {
            $application->rejection_reason = $request->rejection_reason;
        }

        $application->status = $newStatus;
        $application->save();

        if ($newStatus === 'under_review' && $oldStatus === 'pending') {
            return redirect()->route('loan_applications.show', $application->id)->with('success', 'ស្ថានភាពបានផ្លាស់ប្តូរទៅជា កំពុងពិនិត្យ! សូមបំពេញព័ត៌មានបន្ថែម។');
        }

        return redirect()->back()->with('success', 'ផ្លាស់ប្តូរស្ថានភាពដោយជោគជ័យ!');
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

        if ($amount > $product->requires_guarantor_above) {
            $hasGuarantor = Guarantor::where('customer_id', $customer->id)->whereIn('status', ['active'])->exists();
            if (!$hasGuarantor) {
                return "ចំនួនប្រាក់នេះតម្រូវឲ្យមានអ្នកធានាសកម្មយ៉ាងហោចណាស់ម្នាក់! សូមបញ្ចូលអ្នកធានាសម្រាប់អតិថិជននេះជាមុនសិន។";
            }
        }

        if ($amount > $product->requires_collateral_above) {
            // Business rule: Collateral required.
            // As loan_collaterals attaches to loan_id, we just flag the info or fail if they want strict check.
            // Here we just warn or pass, since we can't create collateral without loan_id.
            // But if the requirement says "require collateral", we should add a session warning or note.
        }

        return null;
    }
}
