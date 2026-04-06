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
        $lastCustomer = Customer::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $lastCustomer ? $lastCustomer->id + 1 : 1;
        $generatedCode = 'CUST-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        
        return view('backend.customer.create', compact('generatedCode'));
    }
    public function store(Request $request)
    {
        // Auto-generate code if not provided or to ensure uniqueness
        $lastCustomer = Customer::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = $lastCustomer ? $lastCustomer->id + 1 : 1;
        $generatedCode = 'CUST-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        $request->merge(['code' => $generatedCode]);

        $request->validate([
            'code' => 'required|unique:customers,code',
            'name' => 'required|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|digits_between:8,15',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|max:255',
            'national_id' => 'nullable|max:50',
            'date_of_birth' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'age_verified' => 'nullable|boolean',
            'occupation' => 'nullable|max:255',
            'monthly_income' => 'nullable|numeric',
            'has_existing_loan' => 'nullable|boolean',
            'credit_score' => 'nullable|integer',
            'type' => 'required|in:individual,business',
            'status' => 'required|boolean',
            'document_path' => 'nullable|file|mimes:pdf,docx,doc|max:2048',
            'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $fileName = null;
        if ($request->hasFile('document_path')) {
            $fileName = time() . '_doc.' . $request->file('document_path')->extension();
            $request->file('document_path')->move(public_path('customer_document'), $fileName);
        }

        $profileName = null;
        if ($request->hasFile('profile')) {
            $profileName = time() . '_profile.' . $request->file('profile')->extension();
            $request->file('profile')->move(public_path('profile'), $profileName);
        }

        $data = $request->except(['document_path', 'profile']);
        $data['document_path'] = $fileName;
        $data['profile'] = $profileName;
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
            'date_of_birth' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'age_verified' => 'nullable|boolean',
            'occupation' => 'nullable|max:255',
            'monthly_income' => 'nullable|numeric',
            'has_existing_loan' => 'nullable|boolean',
            'credit_score' => 'nullable|integer',
            'type' => 'required|in:individual,business',
            'status' => 'required|boolean',
            'document_path' => 'nullable|file|mimes:pdf,docx,doc|max:2048',
            'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('document_path');
        $data['age_verified'] = $request->has('age_verified');
        $data['has_existing_loan'] = $request->has('has_existing_loan');

        if ($request->hasFile('document_path')) {
            $fileName = time() . '_doc.' . $request->file('document_path')->extension();
            $request->file('document_path')->move(public_path('customer_document'), $fileName);
            $data['document_path'] = $fileName;
        }

        if ($request->hasFile('profile')) {
            $profileName = time() . '_profile.' . $request->file('profile')->extension();
            $request->file('profile')->move(public_path('profile'), $profileName);
            $data['profile'] = $profileName;
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
