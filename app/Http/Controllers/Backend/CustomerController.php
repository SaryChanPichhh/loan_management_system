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
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('national_id', 'like', "%{$search}%");
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
        $validated = $request->validate(Customer::validationRules());

        $fileName = null;

        if ($request->hasFile('document_path')) {
            $fileName = time() . '.' . $request->document_path->extension();
            $request->document_path->move(public_path('uploads'), $fileName);
        }

        Customer::create([
            ...$validated,
            'document_path' => $fileName,
            'created_by' => auth()->id(),
        ]);

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
        $validated = $request->validate(Customer::validationRules($customer->id));

        if ($request->hasFile('document_path')) {
            $fileName = time() . '.' . $request->document_path->extension();
            $request->document_path->move(public_path('uploads'), $fileName);
            $customer->document_path = $fileName;
        }

        $customer->update($validated);

        return redirect()->route('customer.index')->with('success', 'Updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Deleted successfully');
    }
}
