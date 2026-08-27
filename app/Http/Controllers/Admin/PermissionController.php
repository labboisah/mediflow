<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->paginate(15);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions|max:255',
            'description' => 'nullable|string',
        ]);

        Permission::create($validated);
        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        $roles = Role::all();
        $permissionRoles = $permission->roles->pluck('id')->toArray();
        return view('admin.permissions.edit', compact('permission', 'roles', 'permissionRoles'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id . '|max:255',
            'description' => 'nullable|string',
        ]);

        $permission->update($validated);
        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
