<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repayment;

class RepaymentController extends Controller
{
    // Repayment list (all / overdue / paid)
    public function index(Request $request)
    {
        $status = $request->get('status'); // paid | unpaid | overdue
        $search = $request->get('search');
        
        $query = Repayment::query();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($search) {
            $query->where('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('loan_reference', 'like', '%' . $search . '%');
        }
        
        $repayments = $query->orderBy('id', 'desc')->paginate(10);

        return view('backend.repayments.index', compact('repayments', 'status', 'search'));
    }

    // Show repayment detail for a loan
    public function show($id)
    {
        return view('backend.repayments.show', compact('id'));
    }

    // Record a new repayment (manual entry)
    public function create($loan_id)
    {
        $customers = \App\Models\Customer::all();
        return view('backend.repayments.create', compact('loan_id', 'customers'));
    }

    // Store repayment info into database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_reference' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string|max:255',
            'status' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        Repayment::create($validated);

        return redirect()->route('repayments.index')->with('success', 'ការបង់ប្រាក់ត្រូវបានរក្សាទុកដោយជោគជ័យ។ (Repayment saved successfully!)');
    }

    // Edit repayment (static demo)
    public function edit($id)
    {
        return view('backend.repayments.edit', compact('id'));
    }

    // Overdue repayments page
    public function overdue()
    {
        return view('backend.repayments.overdue');
    }
}
