<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index() {
        return view('admin.departments.index',['departments'=>Department::all()]);
    }

    public function create() {
        return view('admin.departments.create',['departments'=>Department::all()]);
    }

    public function edit($departmentId) {
        return view('admin.departments.edit',['department'=>Department::find($departmentId)]);
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
