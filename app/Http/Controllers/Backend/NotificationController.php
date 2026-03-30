<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Customer;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::with('customer')->orderBy('id', 'desc')->paginate(10);
        return view('backend.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('backend.notifications.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string',
            'customer_id' => 'required|exists:customers,id'
        ]);

        Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'customer_id' => $request->customer_id,
            'is_read' => false
        ]);

        return redirect()->route('notification.index')->with('success', 'Notification assigned to customer successfully');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification deleted successfully');
    }
}
