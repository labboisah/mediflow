<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ward;

class WardController extends Controller
{
    public function index() {
        return view('admin.wards.index',['wards'=>Ward::all()]);
    }

    public function create() {
        return view('admin.wards.create',['wards'=>Ward::all()]);
    }

    public function edit($wardId) {
        return view('admin.wards.edit',['ward'=>Ward::find($wardId)]);
    }

    public function store(Request $request) {
        $request->validate([
            'name'=>'required',
            'capacity'=>'required',
            'price'=>'required',
            ]);

        $ward = Ward::firstOrCreate([
            'name'=>$request->name,
            'price'=>$request->price,
            'capacity'=>$request->capacity,
            ]);

            for($capacity = 1; $capacity <= $ward->capacity; $capacity++){
                $ward->beds()->create(['bed_no'=>$this->format($capacity)]);
            }

        return redirect()->route('admin.wards.index')->with('success', 'Ward Registered');
    }

    public function update(Request $request, Ward $ward) {
        $request->validate([
            'name'=>'required',
            'capacity'=>'required',
            'price'=>'required',
            ]);

        $ward->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'capacity'=>$request->capacity,
            ]);

            foreach($ward->beds as $bed){
                $bed->delete();
            }

            for($capacity = 1; $capacity <= $ward->capacity; $capacity++){
                $ward->beds()->create(['bed_no'=>$this->format($capacity)]);
            }

        return redirect()->route('admin.wards.index')->with('success', 'Ward Updated');
    }

    public function destroy(ward $ward) {
        
        foreach($ward->beds as $bed){
            $bed->delete();
        }

        $ward->delete();

        return redirect()->route('admin.wards.index')->with('success', 'Ward Deleted');
    }

    private function format($number) {
        if($number <= 9){
            $number = '0'.$number;
        }
        return $number;
    }
}
