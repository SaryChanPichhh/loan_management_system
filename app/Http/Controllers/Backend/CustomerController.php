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
            'gender' => 'required',
            'phone' => 'required|digits_between:8,15',
            'address' => 'nullable|max:255',
            'type' => 'required',
            'status' => 'required|boolean',
            'document' => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        $fileName = null;

        if ($request->hasFile('document')) {
            $fileName = time() . '.' . $request->document->extension();
            $request->document->move(public_path('uploads'), $fileName);
        }

        Customer::create([
            ...$request->all(),
            'document' => $fileName
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
        $request->validate([
            'code' => 'required|unique:customers,code,' . $customer->id,
            'name' => 'required|max:255',
            'gender' => 'required',
            'phone' => 'required|digits_between:8,15',
            'address' => 'nullable|max:255',
            'type' => 'required',
            'status' => 'required|boolean',
            'document' => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        if ($request->hasFile('document')) {
            $fileName = time() . '.' . $request->document->extension();
            $request->document->move(public_path('uploads'), $fileName);
            $customer->document = $fileName;
        }

        $customer->update($request->except('document'));

        return redirect()->route('customer.index')->with('success', 'Updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Deleted successfully');
    }
}
