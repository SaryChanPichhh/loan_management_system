<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class DashboardController extends Controller
{
    public function index()
    {
        return view('backend.dashboard.index');
    }
}
