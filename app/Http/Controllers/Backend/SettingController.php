<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExchangeRate;

class SettingController extends Controller
{
    public function company_profile()
    {
        return view("backend.settings.company_profile");
    }

    public function exchange_rate()
    {
        $exchange_rates = ExchangeRate::orderBy('id', 'desc')->get();
        return view("backend.settings.exchange-rate.index", compact('exchange_rates'));
    }

    public function exchange_rate_insert()
    {
        return view("backend.settings.exchange-rate.create");
    }

    public function exchange_rate_store(Request $request)
    {
        $request->validate([
            'base_currency' => 'required|string|max:255',
            'target_currency' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'exchange_date' => 'required|date',
            'source' => 'nullable|string|max:255',
            'created_by' => 'required|string|max:255',
            'status' => 'required|boolean',
            'document' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('exchange_rates', 'public');
        }

        ExchangeRate::create($data);

        return redirect()->route('settings.exchange_rate')->with('success', 'Exchange rate created successfully');
    }

    public function exchange_rate_edit($id)
    {
        $exchange_rate = ExchangeRate::findOrFail($id);
        return view("backend.settings.exchange-rate.edit", compact('exchange_rate'));
    }

    public function exchange_rate_update(Request $request, $id)
    {
        $request->validate([
            'base_currency' => 'required|string|max:255',
            'target_currency' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'exchange_date' => 'required|date',
            'source' => 'nullable|string|max:255',
            'created_by' => 'required|string|max:255',
            'status' => 'required|boolean',
            'document' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',
        ]);

        $exchange_rate = ExchangeRate::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('exchange_rates', 'public');
        }

        $exchange_rate->update($data);

        return redirect()->route('settings.exchange_rate')->with('success', 'Exchange rate updated successfully');
    }

    public function exchange_rate_delete($id)
    {
        $exchange_rate = ExchangeRate::findOrFail($id);
        
        // Optionally delete the document if it exists: 
        // if($exchange_rate->document){ \Storage::disk('public')->delete($exchange_rate->document); }
        
        $exchange_rate->delete();

        return redirect()->route('settings.exchange_rate')->with('success', 'Exchange rate deleted successfully');
    }
}
