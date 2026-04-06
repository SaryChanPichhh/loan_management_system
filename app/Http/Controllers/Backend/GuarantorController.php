<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Guarantor;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuarantorController extends Controller
{
    public function index()
    {
        $guarantors = Guarantor::with('customer')->orderBy('id', 'desc')->get();
        return view('backend.guarantors.index', compact('guarantors'));
    }

    public function create()
    {
        $customers = Customer::where('status', 1)->get();
        return view('backend.guarantors.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'income' => 'nullable|numeric|min:0',
            'relationship' => 'nullable|string|max:100',
            'guarantor_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'document_path' => 'nullable|file|mimes:docx,pdf|max:2048',
            'status' => 'required|in:active,released,defaulted',
        ]);

        $data = $request->except(['guarantor_profile', 'document_path']);

        if ($request->hasFile('guarantor_profile')) {
            $profileName = time() . '_profile.' . $request->file('guarantor_profile')->extension();
            $request->file('guarantor_profile')->move(public_path('guarantor_profile'), $profileName);
            $data['guarantor_profile'] = $profileName;
        }

        if ($request->hasFile('document_path')) {
            $docName = time() . '_doc.' . $request->file('document_path')->extension();
            $request->file('document_path')->move(public_path('guarantor_document'), $docName);
            $data['document_path'] = $docName;
        }

        Guarantor::create($data);

        return redirect()->route('guarantors.index')->with('success', 'បង្កើតអ្នកធានាដោយជោគជ័យ!');
    }

    public function show($id)
    {
        $guarantor = Guarantor::with('customer')->findOrFail($id);
        return view('backend.guarantors.show', compact('guarantor'));
    }

    public function edit($id)
    {
        $guarantor = Guarantor::findOrFail($id);
        $customers = Customer::where('status', 1)->get();
        return view('backend.guarantors.edit', compact('guarantor', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $guarantor = Guarantor::findOrFail($id);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'full_name' => 'required|string|max:255',
            'national_id' => 'required|string|max:50',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'income' => 'nullable|numeric|min:0',
            'relationship' => 'nullable|string|max:100',
            'guarantor_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'document_path' => 'nullable|file|mimes:docx,pdf|max:2048',
            'status' => 'required|in:active,released,defaulted',
        ]);

        $data = $request->except(['guarantor_profile', 'document_path']);

        if ($request->hasFile('guarantor_profile')) {
            // Delete old profile if exists
            if ($guarantor->guarantor_profile && file_exists(public_path('guarantor_profile/' . $guarantor->guarantor_profile))) {
                unlink(public_path('guarantor_profile/' . $guarantor->guarantor_profile));
            }
            $profileName = time() . '_profile.' . $request->file('guarantor_profile')->extension();
            $request->file('guarantor_profile')->move(public_path('guarantor_profile'), $profileName);
            $data['guarantor_profile'] = $profileName;
        }

        if ($request->hasFile('document_path')) {
            // Delete old document if exists
            if ($guarantor->document_path && file_exists(public_path('guarantor_document/' . $guarantor->document_path))) {
                unlink(public_path('guarantor_document/' . $guarantor->document_path));
            }
            $docName = time() . '_doc.' . $request->file('document_path')->extension();
            $request->file('document_path')->move(public_path('guarantor_document'), $docName);
            $data['document_path'] = $docName;
        }

        $guarantor->update($data);

        return redirect()->route('guarantors.index')->with('success', 'កែសម្រួលអ្នកធានាដោយជោគជ័យ!');
    }

    public function destroy($id)
    {
        $guarantor = Guarantor::findOrFail($id);
        
        if ($guarantor->document_path && Storage::disk('public')->exists($guarantor->document_path)) {
            Storage::disk('public')->delete($guarantor->document_path);
        }

        $guarantor->delete();

        return redirect()->route('guarantors.index')->with('success', 'លុបអ្នកធានាដោយជោគជ័យ!');
    }
}
