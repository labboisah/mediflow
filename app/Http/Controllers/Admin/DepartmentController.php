<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index() {
        return view('admin.departments.index', [
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    public function create() {
        return view('admin.departments.create');
    }

    public function edit($departmentId) {
        return view('admin.departments.edit', [
            'department' => Department::findOrFail($departmentId),
        ]);
    }

    public function show(Department $department) {
        $routeName = request()->route()?->getName() ?? '';
        $prefix = str_starts_with($routeName, 'medical-director.') ? 'medical-director' : 'admin';

        return redirect()->route("{$prefix}.departments.edit", $department);
    }

    public function store(Request $request) {
        $request->validate(['name'=>'required']);

        Department::firstOrCreate(['name'=>$request->name]);

        return redirect()->route('admin.departments.index')->with('success', 'Department Registered');
    }

    public function update(Request $request, Department $department) {
        $request->validate(['name'=>'required']);

        $department->update(['name'=>$request->name]);

        return redirect()->route('admin.departments.index')->with('success', 'Department Updated');
    }

    public function destroy(Department $department) {
        

        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department Deleted');
    }
}
