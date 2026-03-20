<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        return view('backend.loans.index', compact('status'));
    }
    
    public function create()
    {
        return view('backend.loans.create');
    }
    
    public function show($id)
    {
        return view('backend.loans.show', compact('id'));
    }
    
    public function edit($id)
    {
        return view('backend.loans.edit', compact('id'));
    }
    
    public function review($id)
    {
        return view('backend.loans.review', compact('id'));
    }
    
    public function payments($id)
    {
        return view('backend.loans.payments', compact('id'));
    }
    
    public function defaulted()
    {
        return view('backend.loans.defaulted');
    }
}

