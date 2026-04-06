<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LoanProduct;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LoanProductController extends Controller
{
    /**
     * Display a listing of loan products.
     */
    public function index()
    {
        $products = LoanProduct::latest()->get();
        return view('backend.loan_products.index', compact('products'));
    }

    /**
     * Show the form for creating a new loan product.
     */
    public function create()
    {
        return view('backend.loan_products.create');
    }

    /**
     * Store a newly created loan product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code'               => 'required|string|max:30|unique:loan_products,product_code',
            'name'                       => 'required|string|max:255',
            'description'                => 'nullable|string',
            'min_amount'                 => 'required|numeric|min:0',
            'max_amount'                 => 'required|numeric|gt:min_amount',
            'interest_rate'              => 'required|numeric|min:0|max:100',
            'interest_type'              => ['required', Rule::in(['FLAT', 'REDUCING_BALANCE', 'COMPOUND'])],
            'max_term_months'            => 'required|integer|min:1|max:360',
            'grace_period_days'          => 'required|integer|min:0',
            'late_fee_rate'              => 'required|numeric|min:0',
            'guarantor_income_multiplier' => 'required|numeric|min:0',
            'requires_collateral_above'  => 'required|numeric|min:0',
            'penalty_rate'               => 'required|numeric|min:0',
            'status'                     => 'nullable',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        LoanProduct::create($validated);

        return redirect()->route('loan_products.index')
            ->with('success', 'ផលិតផលកម្ចីត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    /**
     * Display the specified loan product.
     */
    public function show(LoanProduct $loanProduct)
    {
        return view('backend.loan_products.show', compact('loanProduct'));
    }

    /**
     * Show the form for editing the specified loan product.
     */
    public function edit(LoanProduct $loanProduct)
    {
        return view('backend.loan_products.edit', compact('loanProduct'));
    }

    /**
     * Update the specified loan product.
     */
    public function update(Request $request, LoanProduct $loanProduct)
    {
        $validated = $request->validate([
            'product_code'               => ['required','string','max:30', Rule::unique('loan_products','product_code')->ignore($loanProduct->id)],
            'name'                       => 'required|string|max:255',
            'description'                => 'nullable|string',
            'min_amount'                 => 'required|numeric|min:0',
            'max_amount'                 => 'required|numeric|gt:min_amount',
            'interest_rate'              => 'required|numeric|min:0|max:100',
            'interest_type'              => ['required', Rule::in(['FLAT', 'REDUCING_BALANCE', 'COMPOUND'])],
            'max_term_months'            => 'required|integer|min:1|max:360',
            'grace_period_days'          => 'required|integer|min:0',
            'late_fee_rate'              => 'required|numeric|min:0',
            'guarantor_income_multiplier' => 'required|numeric|min:0',
            'requires_collateral_above'  => 'required|numeric|min:0',
            'penalty_rate'               => 'required|numeric|min:0',
            'status'                     => 'nullable',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        $loanProduct->update($validated);

        return redirect()->route('loan_products.index')
            ->with('success', 'ផលិតផលកម្ចីត្រូវបានធ្វើបច្ចុប្បន្នភាពដោយជោគជ័យ!');
    }

    /**
     * Toggle the status of the specified loan product.
     */
    public function toggleStatus(LoanProduct $loanProduct)
    {
        $loanProduct->update(['status' => !$loanProduct->status]);

        $msg = $loanProduct->status
            ? 'ផលិតផលកម្ចីត្រូវបានបើកដំណើរការ!'
            : 'ផលិតផលកម្ចីត្រូវបានបិទ!';

        return redirect()->route('loan_products.index')->with('success', $msg);
    }

    /**
     * Remove the specified loan product.
     */
    public function destroy(LoanProduct $loanProduct)
    {
        try {
            $loanProduct->delete();

            return redirect()->route('loan_products.index')
                ->with('success', 'ផលិតផលកម្ចីត្រូវបានលុបដោយជោគជ័យ!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('loan_products.index')
                    ->with('error', 'មិនអាចលុបផលិតផលនេះបានទេ ព្រោះវាជាប់ពាក់ព័ន្ធជាមួយកម្ចីដែលមានស្រាប់!');
            }
            throw $e;
        }
    }
}
