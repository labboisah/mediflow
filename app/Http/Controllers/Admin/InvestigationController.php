<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Investigation;
use App\Models\Department;

class InvestigationController extends Controller
{
    public function index() {
        return view('admin.investigations.index',['departments'=>Department::all()]);
    }

    public function create() {
        return view('admin.investigations.create',['departments'=>Investigation::all()]);
    }

    public function edit($investigationId) {
        return view('admin.investigations.edit',['investigation'=>Investigation::find($investigationId)]);
    }

    public function store(Request $request) {
        $request->validate([
            'name'=>'required',
            'price'=>'required',
            'investigation_type'=>'required',
            ]);

        Investigation::firstOrCreate([
            'name'=>$request->name,
            'price'=>$request->price,
            'investigation_type_id'=>$request->investigation_type,
        ]);

        return redirect()->route('admin.investigations.index')->with('success', 'Investigation Registered');
    }

    public function update(Request $request, Investigation $investigation) {
        
        $request->validate([
            'name'=>'required',
            'price'=>'required',
            'investigation_type'=>'required',
            ]);

        $investigation->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'investigation_type_id'=>$request->investigation_type,
            ]);

        return redirect()->route('admin.investigations.index')->with('success', 'Investigation Updated');
    }

    public function destroy(Investigation $investigation) {
        
        if($investigation->investigationRequests->count() < 1){
            $investigation->delete();
            $message = 'Investigation Deleted';
        }else{
            $message = 'Investigation has request record';
        }
        

        return redirect()->route('admin.investigations.index')->with('success', $message);
    }
}
