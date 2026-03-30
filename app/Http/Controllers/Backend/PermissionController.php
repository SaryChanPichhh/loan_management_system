<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get();
        return view('backend.permission.index', compact('permissions'));
    }

    public function create()
    {
        return view('backend.permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug',
            'module' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Permission::create($request->all());

        return redirect()->route('permission.index')->with('success', 'បង្កើតសិទ្ធិបានជោគជ័យ។');
    }

    public function edit(Permission $permission)
    {
        return view('backend.permission.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'module' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $permission->update($request->all());

        return redirect()->route('permission.index')->with('success', 'កែប្រែសិទ្ធិបានជោគជ័យ។');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permission.index')->with('success', 'លុបសិទ្ធិបានជោគជ័យ។');
    }
}
