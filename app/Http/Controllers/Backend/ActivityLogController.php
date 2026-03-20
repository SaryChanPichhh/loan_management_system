<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::orderBy('id', 'desc')->paginate(10);
        return view('backend.activity_log.index', compact('logs'));
    }
}
