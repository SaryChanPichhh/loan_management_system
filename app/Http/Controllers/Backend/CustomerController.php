<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::latest();

        if ($request->ajax()) {
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            }
            $customers = $query->get();
            return view('backend.customer.partials.table', compact('customers'))->render();
        }

        $customers = $query->get();
        return view('backend.customer.index', compact('customers'));
    }
    public function create()
    {
        return view('backend.customer.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:customers,code',
            'name' => 'required|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|digits_between:8,15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|max:255',
            'national_id' => 'nullable|max:50',
            'date_of_birth' => 'nullable|date',
            'age_verified' => 'nullable|boolean',
            'occupation' => 'nullable|max:255',
            'monthly_income' => 'nullable|numeric',
            'has_existing_loan' => 'nullable|boolean',
            'credit_score' => 'nullable|integer',
            'type' => 'required',
            'status' => 'required|boolean',
            'document_path' => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        $fileName = null;

        if ($request->hasFile('document_path')) {
            $fileName = time() . '.' . $request->file('document_path')->extension();
            $request->file('document_path')->move(public_path('uploads'), $fileName);
        }

        $data = $request->except('document_path');
        $data['document_path'] = $fileName;
        $data['created_by'] = auth()->id();
        $data['age_verified'] = $request->has('age_verified');
        $data['has_existing_loan'] = $request->has('has_existing_loan');

        Customer::create($data);

        return redirect()->route('customer.index')->with('success', 'Created successfully');
    }

    public function show(Customer $customer)
    {
        // Redirect to edit view since there is no standalone show view currently
        return redirect()->route('customer.edit', $customer->id);
    }

    public function edit(Customer $customer)
    {
        return view('backend.customer.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'code' => 'required|unique:customers,code,' . $customer->id,
            'name' => 'required|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|digits_between:8,15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|max:255',
            'national_id' => 'nullable|max:50',
            'date_of_birth' => 'nullable|date',
            'age_verified' => 'nullable|boolean',
            'occupation' => 'nullable|max:255',
            'monthly_income' => 'nullable|numeric',
            'has_existing_loan' => 'nullable|boolean',
            'credit_score' => 'nullable|integer',
            'type' => 'required',
            'status' => 'required|boolean',
            'document_path' => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        $data = $request->except('document_path');
        $data['age_verified'] = $request->has('age_verified');
        $data['has_existing_loan'] = $request->has('has_existing_loan');

        if ($request->hasFile('document_path')) {
            $fileName = time() . '.' . $request->file('document_path')->extension();
            $request->file('document_path')->move(public_path('uploads'), $fileName);
            $data['document_path'] = $fileName;
        }

        $customer->update($data);

        return redirect()->route('customer.index')->with('success', 'Updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Deleted successfully');
    }
}
