<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles', 'permissions')->get();
        $permissionsByModule = Permission::all()->groupBy('module');

        return view('backend.role.index', compact('users', 'permissionsByModule'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->sync($request->permissions ?? []);

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions updated successfully'
        ]);
    }

    public function editUserPermissions($id)
    {
        $user = User::with('permissions', 'roles.permissions')->findOrFail($id);
        $permissionsByModule = Permission::all()->groupBy('module');

        return view('backend.role.edit', compact('user', 'permissionsByModule'));
    }

    public function updateUserPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->permissions()->sync($request->permissions ?? []);

        return redirect()->route('role.index')->with('success', 'សិទ្ធិអ្នកប្រើប្រាស់ត្រូវបានរក្សាទុកដោយជោគជ័យ');
    }
}
